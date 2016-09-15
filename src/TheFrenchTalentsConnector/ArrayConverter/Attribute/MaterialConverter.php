<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter\Attribute;

use Kiboko\Component\Connector\Discoverer\AttributeOptionDiscoverer;
use Kiboko\Component\Connector\Discoverer\OptionDiscovererInterface;
use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class MaterialConverter
    implements StandardArrayConverterInterface
{
    /**
     * @var AttributeOptionDiscoverer
     */
    private $materialMapper;

    /**
     * ColorConverter constructor.
     * @param OptionDiscovererInterface $materialMapper
     */
    public function __construct(
        OptionDiscovererInterface $materialMapper
    ) {
        $this->materialMapper = $materialMapper;
    }

    /**
     * @param array $item
     * @param array $options
     * @return array
     */
    public function convert(array $item, array $options = [])
    {
        return [
            'material'             => $this->materialMapper->find($item['Product_Material'], 'fr_FR'),
            'additional_materials' => '',
        ];
    }
}
