<?php
namespace DvBarcodeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DvBarcodeExtension extends Extension
{
    /**
     * @param  array $configs
     * @param  ContainerBuilder $container
     * @return void
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->loadConfiguration($configs, $container);
    }

    /**
     * Loads the configuration in, with any defaults
     *
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @throws \Exception
     */
    protected function loadConfiguration(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new DvBarcodeConfiguration(), $configs);
        $optionDef = new Definition('DvBarcodeBundle\Twig\SvgBarcodeExtension');
        $optionDef->addTag('twig.extension');
        $container->setDefinition('DvBarcodeBundle\Twig\SvgBarcodeExtension', $optionDef);
        $optionDef = new Definition('DvBarcodeBundle\Twig\SvgExtension');
        $optionDef->addMethodCall('setFont', [ __DIR__ . '/../Fonts/' . $config['font'] . '.svg' ]);
        $optionDef->addTag('twig.extension');
        $container->setDefinition('DvBarcodeBundle\Twig\SvgExtension', $optionDef);
    }
}