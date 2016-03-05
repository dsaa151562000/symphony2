<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        //設定ファイルのエントリポイントを定めます。
        //これが❶で、エクステンションのエイリアスと同じ名前を指定します。
        //このエントリポイントをルートノードとして取得して います。以降はこのルートノードの下に子ノードをつなげていきます。
        $rootNode = $treeBuilder->root('app');

        $rootNode
            ->children()
                ->arrayNode('members')
                   ->useAttributeAsKey('name')
                     ->prototype('array')
                        ->children()
                           ->scalarNode('part')->isRequired()->end()
                           ->scalarNode('joinedDate')->isRequired()->end()
                         ->end()
                     ->end()
                   ->end()
            ->end();

        return $treeBuilder;
    }
}
