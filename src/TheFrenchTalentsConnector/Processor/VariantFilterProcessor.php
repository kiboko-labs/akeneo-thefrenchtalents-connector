<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Processor;

use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\ItemProcessorInterface;
use Akeneo\Component\Batch\Step\StepExecutionAwareInterface;
use Doctrine\Common\Collections\Collection;
use Kiboko\Component\Connector\ConfigurationAwareTrait;
use Kiboko\Component\Connector\NameAwareTrait;
use Kiboko\Component\Connector\StepExecutionAwareTrait;

class VariantFilterProcessor
    extends AbstractConfigurableStepElement
    implements ItemProcessorInterface, StepExecutionAwareInterface
{
    use StepExecutionAwareTrait;
    use ConfigurationAwareTrait;
    use NameAwareTrait;

    /**
     * @var Collection
     */
    private $skuCache;

    /**
     * VariantFilterProcessor constructor.
     *
     * @param Collection $skuCache
     */
    public function __construct(Collection $skuCache)
    {
        $this->skuCache = $skuCache;
    }

    /**
     * @return array
     */
    public function getConfigurationFields()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function process($item)
    {
        if (!$this->skuCache->containsKey($item['sku'])) {
            return null;
        }

        return $item;
    }
}
