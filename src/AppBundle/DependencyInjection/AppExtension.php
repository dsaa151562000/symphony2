<?php

namespace AppBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use AppBundle\Entity\MemberCollection;

//エクステンションとは、サービスコンテナに対して何らかの拡張を行うための場所で、
//YAMLファイルやXMLファイルからサービス定義を読み込んだり、設定に基づ いてサービスの構成を行ったりします。
class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
     // ❶YamlFileLoaderを準備する
     //resources/configディレクトリへの相対パスを渡して YamlFileLoaderを初期化し、
    $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

   //❷で services.yml を読み込んでいます
    $loader->load('services.yml');


    $config = $this->processConfiguration(new Configuration(), $configs);


    //コレクションの定義をまとめて行う buildMemberCollectionDefinition( )メソッドを呼び出しています。
    $this->buildMemberCollectionDefinition($container, $config['members']);

    }
    //これにより、ContainerBuilderオブジェクトである $containerに、サービスの定義が読み込まれます。

    private function buildMemberCollectionDefinition(ContainerBuilder $container, $memberList)
    {

        //最初にMemberCollectionクラスをサービスとして登録するために、$container->register( )メソッドを呼び出しています
        $collectionDefinition = $container->register('app.member_collection', MemberCollection::class);

        foreach ($memberList as $name => $memberInfo)
        {
            //コレクションは、すべてのメンバーの一覧を保持している必要があります。
            //このために、サービスコンテナ側で、サービスのインスタンス化時に全メンバーを登録するようにします。
          $collectionDefinition->addMethodCall('addMember', [$name, $memberInfo['part'], $memberInfo['joinedDate']]);
        }

    }



    public function getAlias()
    {
        //❸の個所では、このエクステンションのエイリアスを指定しています。
        //この名前は、 app/config/config.yml に設定を記述するときに使います
        return 'app';
    }
}