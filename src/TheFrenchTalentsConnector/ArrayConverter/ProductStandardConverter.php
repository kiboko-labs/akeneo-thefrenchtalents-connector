<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class ProductStandardConverter
    implements StandardArrayConverterInterface
{
    /**
     * @var StandardArrayConverterInterface[]|Collection
     */
    protected $childrenProductConverters;

    /**
     * ProductStandardConverter constructor.
     * @param StandardArrayConverterInterface[]|Collection|null $childrenProductConverters
     */
    public function __construct(
        Collection $childrenProductConverters = null
    ) {
        if ($childrenProductConverters === null) {
            $this->childrenProductConverters = new ArrayCollection();
        } else {
            $this->childrenProductConverters = $childrenProductConverters;
        }
    }

    /**
     * @param array $item
     * @param array $options
     * @return array
     */
    public function convert(array $item, array $options = [])
    {
        $output = [
            'sku'                                    => sprintf('TFT%s', $item['Unique_ID']),
//            'groups'                                 => (isset($item['Parent_ID']) && !empty($item['Parent_ID'])) ? sprintf('TFT%s', $item['Parent_ID']) : null,
            'external_sku'                           => $item['Unique_ID'],
            'ean13'                                  => $item['EAN'],
            'name-fr_FR'                             => $item['Title'],
            'name-en_US'                             => $item['English_Product_Name'],
            'name-en_GB'                             => $item['English_Product_Name'],
            'description-fr_FR-ecommerce_luni'       => $item['Description'],
            'description-en_US-ecommerce_luni'       => $item['English_Product_Description'],
            'description-en_GB-ecommerce_luni'       => $item['English_Product_Description'],
            'original_description'                   => $item['Description'],
            'short_description-fr_FR-ecommerce_luni' => $item['Description'],
            'short_description-en_US-ecommerce_luni' => $item['English_Product_Description'],
            'short_description-en_GB-ecommerce_luni' => $item['English_Product_Description'],
            'brand'                                  => $item['Brand'],
//            'excluded'                               => false,
        ];

        foreach ($this->childrenProductConverters as $converter) {
            $output = array_merge(
                $output,
                $converter->convert($item, $options)
            );
        }

        return $output;
    }

    /**
     * @param StandardArrayConverterInterface $child
     */
    public function addChildConverter(StandardArrayConverterInterface $child)
    {
        $this->childrenProductConverters->add($child);
    }
}
