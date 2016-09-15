<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter;

use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class MessedUpSizeVariationFixerArrayConverter
    implements StandardArrayConverterInterface
{
    private $messedUpBrands = [
        930
    ];

    /**
     * @var StandardArrayConverterInterface
     */
    private $decorated;

    /**
     * MessedUpSizeVariationFixerArrayConverter constructor.
     * @param StandardArrayConverterInterface $decorated
     */
    public function __construct(StandardArrayConverterInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @param array $item
     * @param array $options
     * @return array
     */
    public function convert(array $item, array $options = [])
    {
        if (in_array($item['BrandId'], $this->messedUpBrands) && !empty($item['Size_Variation'])) {
            $item['Size_Variation'] = preg_replace('/^P(\d+)$/', 'T\\1', $item['Size_Variation']);
        }

        return $this->decorated->convert($item, $options);
    }
}
