<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use AppBundle\Entity\Inquiry;
use League\Csv\Writer;


/**
 * @Route("/admin/inquiry")
 */
class AdminInquiryListController extends Controller
{
    /**
     * @Route("/search.{_format}",
     *      defaults={"_format": "html"},
     *      requirements={
     *         "_format": "html|csv",
     *     }
     * )
     */
    public function indexAction(Request $request, $_format)
    {
       //❸検索キーワード入力フォームの処理
        $form = $this->createSearchForm();
        $form->handleRequest($request);
        $keyword = null;

        if ($form->isValid()) {
            $keyword = $form->get('search')->getData();

        }

        // ❸Doctrineを使ってお問い合わせ一覧をデータベースから取得
        // ❶エンティティマネージャを取得
        $em = $this->getDoctrine()->getManager();
        //❷エンティティマネージャから、エンティティリポジトリを取得
        $inquiryRepository = $em->getRepository('AppBundle:Inquiry');

        //InquiryRepositoryクラスのfindAllByKeyword( )メソッドに実装します。
        //❸エンティティリポジトリのfindAllByKeyword( )メソッドを実行して、情報を取得
        $inquiryList = $inquiryRepository->findAllByKeyword($keyword);


        if ($_format == 'csv') {
            //createCsv( )メソッドでCSV形式のデータを用意してから、CSV 用のレスポンスオブジェクトを作っています
            $response = new Response($this->createCsv($inquiryList));

            $d = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'inquiry_list.csv'
            );

            $response->headers->set('Content-Disposition', $d);

            return $response;


        }


        // ❹お問い合わせ一覧を渡してテンプレートをレンダリング
         return $this->render('Admin/Inquiry/index.html.twig',
             [
                 'form' => $form->createView(),
                 'inquiryList' => $inquiryList
              ]
         );
    }


    //❷検索キーワード入力フォーム作成
    private function createSearchForm()
    {
        return $this->createFormBuilder()
            ->add('search', 'search')
            ->add('submit', 'button', [
                'label' => '検索', ])
            ->getForm();
    }

    //createCsv( )メソッドでCSV形式のデータを用意
    private function createCsv($inquiryList)
    {
        /** @var Writer $writer */
        $writer = Writer::createFromString('','');
        $writer->setNewline("\r\n");

        foreach ($inquiryList as $inquiry) {
            /** @var Inquiry $inquiry */
            $writer->insertOne([
                $inquiry->getId(),
                $inquiry->getName(),
                $inquiry->getEmail()
            ]);
        }

        return (string)$writer;
    }





}
