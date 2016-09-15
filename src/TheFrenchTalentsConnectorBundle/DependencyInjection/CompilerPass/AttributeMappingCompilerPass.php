<?php

namespace Kiboko\Bundle\TheFrenchTalentsConnectorBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class AttributeMappingCompilerPass
    implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('kiboko_tft_connector.array_converter.flat.product')) {
            return;
        }

        $definition = $container->findDefinition(
            'kiboko_tft_connector.array_converter.flat.product'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'kiboko_tft_connector.array_converter'
        );

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addChildConverter',
                [new Reference($id)]
            );
        }
    }
}
