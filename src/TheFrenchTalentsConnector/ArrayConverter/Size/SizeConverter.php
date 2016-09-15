<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter\Size;

use Kiboko\Component\Connector\Discoverer\AttributeOptionDiscoverer;
use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class SizeConverter
    implements StandardArrayConverterInterface
{
    /**
     * @var string
     */
    private $attribute;

    /**
     * @var AttributeOptionDiscoverer
     */
    private $discoverer;

    /**
     * @param string $attribute
     * @param AttributeOptionDiscoverer $discoverer
     */
    public function __construct(
        $attribute,
        AttributeOptionDiscoverer $discoverer
    ) {
        $this->attribute = $attribute;

        $this->discoverer = $discoverer;
    }

    /**
     * @param array $item
     * @param array $options
     * @return array
     */
    public function convert(array $item, array $options = [])
    {
        if (($size = $this->convertSize($item['Size_Variation'])) === null) {
            return [];
        }

        return [
            $this->attribute => $size,
        ];
    }

    protected function convertSize($size)
    {
        return $this->discoverer->find($size, 'fr_FR');
    }
}
