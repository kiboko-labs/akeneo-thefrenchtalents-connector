<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter\Attribute;

use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class InventoryConverter
    implements StandardArrayConverterInterface
{
    /**
     * @param array $item
     * @param array $options
     * @return array
     */
    public function convert(array $item, array $options = [])
    {
        return [
            'stock'              => $this->parseInt($item['Quantity_in_Stock']),
            'availability'       => $this->findInventoryStatus($item),
            'weight_metric'      => $this->parseFloat($item['Weight']),
            'weight_metric-unit' => 'GRAM',
        ];
    }

    /**
     * @param array $item
     * @return string
     */
    private function findInventoryStatus(array $item)
    {
        $quantity = $this->parseInt($item['Quantity_in_Stock']);
        if ($quantity > 0) {
            return 'availability_in_stock';
        }

        return 'availability_out_of_stock';
    }

    /**
     * @param string $item
     * @return float
     */
    private function parseInt($item)
    {
        return (int) $item;
    }

    /**
     * @param string $item
     * @return float
     */
    private function parseFloat($item)
    {
        return (float) $item;
    }
}
