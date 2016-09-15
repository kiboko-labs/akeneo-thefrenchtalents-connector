<?php

namespace Kiboko\Bundle\TheFrenchTalentsConnectorBundle\Transformer;

class PrefixedSkuTransformer
    implements SkuTransformerInterface
{
    public function transform($externalSku)
    {
        if (empty($externalSku)) {
            return null;
        }

        return sprintf('TFT%s', $externalSku);
    }
}
