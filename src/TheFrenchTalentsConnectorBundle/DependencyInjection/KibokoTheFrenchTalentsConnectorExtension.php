<?php

namespace Kiboko\Bundle\TheFrenchTalentsConnectorBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

class KibokoTheFrenchTalentsConnectorExtension
    extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('array_converters.yml');
        $loader->load('option_discoverers.yml');
        $loader->load('size_discoverers.yml');
        $loader->load('families.yml');
        $loader->load('readers.yml');
        $loader->load('processors.yml');
        $loader->load('writers.yml');
        $loader->load('cache.yml');
        $loader->load('managers.yml');
    }
}
