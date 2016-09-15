<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter;

use Akeneo\Component\Batch\Item\InvalidItemException;
use Akeneo\Component\Batch\Job\RuntimeErrorException;
use Doctrine\Common\Collections\Collection;
use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;
use Pim\Component\Connector\Exception\ArrayConversionException;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FamilyConverter
    implements StandardArrayConverterInterface
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Collection
     */
    private $axisCache;

    /**
     * FamilyConverter constructor.
     * @param ContainerInterface $container
     * @param Collection $axisCache
     * @param FileLoader $loader
     * @param $filename
     */
    public function __construct(
        ContainerInterface $container,
        Collection $axisCache,
        FileLoader $loader,
        $filename
    ) {
        $this->axisCache = $axisCache;
        $this->configuration = $loader->load($filename);
        $this->container = $container;
    }

    /**
     * @param array $conditions
     * @param array $item
     * @return bool
     */
    private function matchesConditions(array $conditions, array $item)
    {
        foreach ($conditions as $case) {
            if (isset($case['universe']) &&
                $case['universe'] !== $item['Univers']
            ) {
                continue;
            }
            if (isset($case['family']) &&
                $case['family'] !== $item['Family']
            ) {
                continue;
            }
            if (isset($case['sub-family']) &&
                $case['sub-family'] !== $item['Sub_Family']
            ) {
                continue;
            }
            if (isset($case['category']) &&
                $case['category'] !== $item['Category']
            ) {
                continue;
            }

            return true;
        }

        return false;
    }

    public function convert(array $item, array $options = [])
    {
        $fields = [];

        foreach ($this->configuration as $familyConfig) {
            if (!$this->matchesConditions($familyConfig['conditions'], $item)) {
                continue;
            }

            $fields = [
                'family' => $familyConfig['code'],
            ];

            if (!isset($familyConfig['sizes']) || !is_array($familyConfig['sizes']) || empty($item['Size_Variation'])) {
                break;
            }

            /** @var string $sizeMapperService */
            foreach ($familyConfig['sizes'] as $sizeMapperService) {
                /** @var StandardArrayConverterInterface */
                $sizeMapper = $this->container->get($sizeMapperService);
                if (!$sizeMapper instanceof StandardArrayConverterInterface) {
                    throw new ArrayConversionException(sprintf('Service \'%s\' does not implement interface %s.',
                        $sizeMapperService, StandardArrayConverterInterface::class));
                }

                $sizeFields = $sizeMapper->convert($item, $options);
                if (empty($sizeFields)) {
                    continue;
                }

                $parentSku = sprintf('TFT%s', $item['Parent_ID']);
                if (!$this->axisCache->containsKey($parentSku)) {
                    $this->axisCache->set($parentSku, array_keys($sizeFields));
                } else {
                    $this->axisCache->set($parentSku, array_unique(array_merge(
                        $this->axisCache->get($parentSku),
                        array_keys($sizeFields)
                    )));
                }

                return array_merge($fields, $sizeFields);
            }

            return $fields;
        }

        if (isset($item['Parent_ID']) && !empty($item['Parent_ID'])) {
            throw new InvalidItemException('No family found for variant.', $item);
        }

        return $fields;
    }
}
