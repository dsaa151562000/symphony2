<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class NotifyUnprocessedInquiryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        //コマンドクラスのconfigure( )メソッドの中で設定します。
        $this
            ->setName('cs:inquiry:notify-unprocessed')
            ->setDescription('未処理お問い合わせ一覧を通知');
        }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //execute( )メソッドの中には、コマンドの処理を記述します。


        //ContainerAware Commandクラスを継承しています。
        //このようにして作成したコマンドクラス内では、
        // $this->getContainer( )メソッドを呼び出すことでサービスコンテナへアクセスでき、
        $container = $this->getContainer();

        $csvBuilder = $this->getContainer()->get('app.inquiry_csv_builder');
        //サービスコンテナのget( )メソッドを使えばサービスを取得できます。
        $em = $container->get('doctrine')->getManager();

        $inquiryRepository = $em->getRepository('AppBundle:Inquiry');

        //￼未処理のお問い合わせ一覧をリポジトリから取得
        $inquiryList = $inquiryRepository->findUnprocessed();


        if (count($inquiryList) > 0) {

            //コマンドからの出力には、$output変数
            $output->writeln(count($inquiryList) . '件の未処理お問い合わせがあります');

            //メール送信の確認処理を実行
            //confirmSendがtrueならば実行
            if ($this->confirmSend($input, $output)) {

                $this->sendMail($inquiryList, $output);
            }
        } else {
            $output->writeln("未処理なし");
        }
    }

    //
    private function confirmSend($input, $output)
    {
        //Symfony の Console コンポーネ ントには、確認プロンプトを出したり、
        //そこでの入力値をバリデーションしたりするための機能が
        // Questionとして組み込まれています。
        $qHelper = $this->getHelper('question');

        //Question クラス
        $question = new Question('通知メールを送信しますか？ [y/n]: ', null);

        //setValidator( )メソッドに、バリデーション時に呼び出すコールバックを渡します
        //引数:入力された値
        //戻り値:正常な場合はバリデーション後の値、エラーがある場合は例外をスロー
        $question->setValidator(function ($answer) {
            //strtolower 大文字を小文字に substrは$answerの0番目の1文字を返す
            $answer = strtolower(substr($answer, 0, 1));

            if (!in_array($answer, ['y', 'n'])) {
                throw new \RuntimeException(
                    'yまたはnを入力してください'
                );
            }
            return $answer;
        });

        //❺許容する試行回数の設定
        $question->setMaxAttempts(3);

        //プロンプトを表示して回答を得る
        //Questionヘルパのask( )メソッドを呼び出します。ask( )メソッドの戻り値は、
        //入力バリデーション後の値です。これが'y'だった場合は、confirmSend( )メソッドの戻り値として
        // true が返るようにしています。
        return $qHelper->ask($input, $output, $question) == 'y';
    }


    private function sendMail($inquiryList, $output)
    {
        $container = $this->getContainer();
        $templating = $container->get('templating');

        $message = \Swift_Message::newInstance()
            ->setSubject('[CS] 未処理お問い合わせ通知')
            ->setFrom('webmaster@example.com')
            ->setTo('admin@example.com')
            ->setBody(
                $templating->render(
                    'mail/admin_unprocessedInquiryList.txt.twig',
                    ['inquiryList' => $inquiryList]
                )
            );

        $container->get('mailer')->send($message);
    }
}
