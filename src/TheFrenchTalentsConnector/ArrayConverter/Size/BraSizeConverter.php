<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter\Size;

use Kiboko\Component\Connector\Discoverer\AttributeOptionDiscoverer;
use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class BraSizeConverter
    implements StandardArrayConverterInterface
{
    /**
     * @var string
     */
    private $cupSizeAttribute;

    /**
     * @var string
     */
    private $chestSizeAttribute;

    /**
     * @var AttributeOptionDiscoverer
     */
    private $cupSizeMapper;

    /**
     * @var AttributeOptionDiscoverer
     */
    private $chestSizeMapper;

    /**
     * @param string $cupSizeAttribute
     * @param string $chestSizeAttribute
     * @param AttributeOptionDiscoverer $cupSizeMapper
     * @param AttributeOptionDiscoverer $chestSizeMapper
     */
    public function __construct(
        $cupSizeAttribute,
        $chestSizeAttribute,
        AttributeOptionDiscoverer $cupSizeMapper,
        AttributeOptionDiscoverer $chestSizeMapper
    ) {
        $this->cupSizeAttribute = $cupSizeAttribute;
        $this->chestSizeAttribute = $chestSizeAttribute;

        $this->cupSizeMapper = $cupSizeMapper;
        $this->chestSizeMapper = $chestSizeMapper;
    }

    /**
     * @param array $item
     * @param array $options
     * @return array
     */
    public function convert(array $item, array $options = [])
    {
        if (!preg_match('/^(?<chest>\\d{2,3})\\s*(?<cup>\\w{1,3})$/', $item['Size_Variation'], $matches)) {
            return [];
        }

        if (($cupSize = $this->cupSizeMapper->find($matches['cup'], 'fr_FR')) === null) {
            return [];
        }
        if (($chestSize = $this->chestSizeMapper->find($matches['chest'], 'fr_FR')) === null) {
            return [];
        }

        return [
            $this->cupSizeAttribute => $cupSize,
            $this->chestSizeAttribute => $chestSize,
        ];
    }
}
