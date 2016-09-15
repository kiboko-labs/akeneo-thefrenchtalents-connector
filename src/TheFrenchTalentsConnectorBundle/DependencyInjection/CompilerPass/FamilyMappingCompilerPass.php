<?php

namespace Kiboko\Bundle\TheFrenchTalentsConnectorBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class FamilyOptionMappingCompilerPass
    implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('kiboko_tft_connector.option_mapper_chain')) {
            return;
        }

        $definition = $container->findDefinition(
            'kiboko_tft_connector.option_mapper_chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'kiboko_tft_connector.option_mapper'
        );

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addMapper',
                    [new Reference($id), $attributes['category'], $attributes['alias']]
                );
            }
        }
    }
}
