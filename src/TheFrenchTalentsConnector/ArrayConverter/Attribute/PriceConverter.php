<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter\Attribute;

use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class PriceConverter
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
            'cost-EUR'                 => $this->parseFloat($item['Wholesale_Price_VAT_excl']),
            'original_price-EUR'       => $this->parseFloat($item['Min_Retailing_Price_VAT_incl']),
            'price-EUR'                => $this->parseFloat($item['Min_Retailing_Price_VAT_incl']),
            'minimal_public_price-EUR' => $this->findMinimalPrice($item),
        ];
    }

    private function findMinimalPrice(array $item)
    {
        $price = $this->parseFloat($item['Min_Retailing_Price_VAT_incl']);
        if (isset($item['Discounted_Price_VAT_included'])) {
            $discountedPrice = $this->parseFloat($item['Discounted_Price_VAT_included']);

            if ($discountedPrice !== null && $discountedPrice > 0 && $discountedPrice < $price) {
                return $discountedPrice;
            }
        }

        return $price;
    }

    /**
     * @param string $item
     * @return float
     */
    private function parseFloat($item)
    {
        return $item === null ? null : (float) $item;
    }
}
