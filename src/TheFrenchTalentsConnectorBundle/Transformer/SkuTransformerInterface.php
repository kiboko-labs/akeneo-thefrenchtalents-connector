<?php

namespace Kiboko\Bundle\TheFrenchTalentsConnectorBundle\Transformer;

interface SkuTransformerInterface
{
    /**
     * @param string $externalSku
     * @return string
     */
    public function transform($externalSku);
}
