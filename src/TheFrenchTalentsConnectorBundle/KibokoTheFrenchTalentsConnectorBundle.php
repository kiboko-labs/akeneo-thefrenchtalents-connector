<?php

namespace Kiboko\Bundle\TheFrenchTalentsConnectorBundle;

use Kiboko\Bundle\TheFrenchTalentsConnectorBundle\DependencyInjection\CompilerPass\AttributeMappingCompilerPass;
use Kiboko\Bundle\TheFrenchTalentsConnectorBundle\DependencyInjection\CompilerPass\FamiliesMappingFileLoaderCompilerPass;
use Kiboko\Bundle\TheFrenchTalentsConnectorBundle\DependencyInjection\CompilerPass\OptionMappingFileLoaderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KibokoTheFrenchTalentsConnectorBundle
    extends Bundle
{
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AttributeMappingCompilerPass());
        $container->addCompilerPass(new OptionMappingFileLoaderCompilerPass());
        $container->addCompilerPass(new FamiliesMappingFileLoaderCompilerPass());
    }
}
