<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter;

use Doctrine\Common\Collections\Collection;
use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class ProductToVariantGroupConverter
    implements StandardArrayConverterInterface
{
    /**
     * @var array
     */
    private $columnsToRemove = [
        'sku',
        'stock',
        'availability',
        'external_sku',
        'VARIANT-groups',
        'family',
        'cup_size_eu',
        'chest_size_fr_es',
        'belt_size_cm',
        'main_size_iso',
        'top_size_fr',
        'hips_size_fr_flat',
        'hips_size_fr',
        'dress_skirt_size_western_eu',
        'shoe_size_eu',
        'shoe_size_uk',
        'shoe_size_us',
        'hips_size_us',
        'hips_size_eu'
    ];

    /**
     * @var Collection
     */
    private $axisCache;

    /**
     * @param Collection $axisCache
     */
    public function __construct(
        Collection $axisCache
    ) {
        $this->axisCache = $axisCache;
    }

    /**
     * @param array $item
     * @param array $options
     * @return array
     */
    public function convert(array $item, array $options = [])
    {
        $axis = [];
        if ($this->axisCache->containsKey($item['sku'])) {
            $axis = $this->axisCache->get($item['sku']);
        }

        $variantFields = [
            'code'        => $item['sku'],
            'type'        => 'VARIANT',
            'label-fr_FR' => $this->formatTitle($item['sku'], $item['name-fr_FR'], $item['brand']),
            'label-en_US' => $this->formatTitle($item['sku'], $item['name-en_US'], $item['brand']),
            'label-en_GB' => $this->formatTitle($item['sku'], $item['name-en_GB'], $item['brand']),
            'axis'        => implode(',', $axis),
        ];

        foreach ($this->columnsToRemove as $column) {
            unset($item[$column]);
        }

        foreach ($axis as $column) {
            unset($item[$column]);
        }

        return array_merge($item, $variantFields);
    }

    /**
     * @param string $sku
     * @param string $name
     * @param string $brand
     * @param int $maximumLength
     * @return string
     */
    private function formatTitle($sku, $name, $brand, $maximumLength = 100)
    {
        $allowedLength = $maximumLength - 6 - mb_strlen($brand, 'UTF-8') - mb_strlen($sku, 'UTF-8');

        if (mb_strlen($name) > $allowedLength) {
            $startLength = floor($allowedLength / 3 * 2) - 5;
            $name = mb_substr($name, 0, $startLength) . '[...]' . mb_substr($name, -($allowedLength - $startLength - 5));
        }

        return sprintf('%s | %s | %s', $sku, $name, $brand);
    }
}
