<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter\Attribute;

use Kiboko\Component\Connector\Discoverer\AttributeOptionDiscoverer;
use Kiboko\Component\Connector\Discoverer\OptionDiscovererInterface;
use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class ColorConverter
    implements StandardArrayConverterInterface
{
    /**
     * @var AttributeOptionDiscoverer
     */
    private $colorMapper;

    /**
     * ColorConverter constructor.
     * @param OptionDiscovererInterface $colorMapper
     */
    public function __construct(
        OptionDiscovererInterface $colorMapper
    ) {
        $this->colorMapper = $colorMapper;
    }

    /**
     * @param array $item
     * @param array $options
     * @return array
     */
    public function convert(array $item, array $options = [])
    {
        return [
            'main_color'        => $this->colorMapper->find($item['Color'], 'fr_FR'),
            'additional_colors' => '',
            'supplier_colors'   => $item['Color'],
        ];
    }
}
