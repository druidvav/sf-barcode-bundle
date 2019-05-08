<?php
namespace DvBarcodeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class DvBarcodeConfiguration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root("dv_barcode");

        $rootNode->
            children()->
                scalarNode("font")->defaultValue('HelveticaMedium')->end()->
            end()
        ;

        return $treeBuilder;
    }
}
