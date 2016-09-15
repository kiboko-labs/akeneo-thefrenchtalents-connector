<?php

namespace Kiboko\Bundle\TheFrenchTalentsConnectorBundle\DependencyInjection\Config;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlMappingLoader
    extends FileLoader
{
    /**
     * @param mixed $resource
     * @param null $type
     * @return array
     * @throws \Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     */
    public function load($resource, $type = null)
    {
        $options = Yaml::parse(file_get_contents($this->locator->locate($resource)));

        if (!isset($options['options']) || !is_array($options['options'])) {
            return [];
        }

        return $options['options'];
    }

    /**
     * @param mixed $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $this->locator->locate($resource),
            PATHINFO_EXTENSION
        );
    }
}
