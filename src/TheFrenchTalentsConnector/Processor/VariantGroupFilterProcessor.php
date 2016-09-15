<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Processor;

use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\ItemProcessorInterface;
use Akeneo\Component\Batch\Step\StepExecutionAwareInterface;
use Akeneo\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Kiboko\Component\Connector\ConfigurationAwareTrait;
use Kiboko\Component\Connector\NameAwareTrait;
use Kiboko\Component\Connector\StepExecutionAwareTrait;
use Pim\Component\Catalog\Model\GroupInterface;
use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class VariantGroupFilterProcessor
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
     * @var StandardArrayConverterInterface
     */
    private $productToVariantGroupConverter;

    /**
     * @var IdentifiableObjectRepositoryInterface
     */
    private $groupRepository;

    /**
     * @var IdentifiableObjectRepositoryInterface
     */
    private $productRepository;

    /**
     * VariantGroupFilterProcessor constructor.
     *
     * @param Collection $skuCache
     * @param StandardArrayConverterInterface $productToVariantGroupConverter
     * @param IdentifiableObjectRepositoryInterface $groupRepository
     * @param IdentifiableObjectRepositoryInterface $productRepository
     */
    public function __construct(
        Collection $skuCache,
        StandardArrayConverterInterface $productToVariantGroupConverter,
        IdentifiableObjectRepositoryInterface $groupRepository,
        IdentifiableObjectRepositoryInterface $productRepository
    ) {
        $this->skuCache = $skuCache;
        $this->productToVariantGroupConverter = $productToVariantGroupConverter;
        $this->groupRepository = $groupRepository;
        $this->productRepository = $productRepository;
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
        if (!$this->skuCache->contains($item['sku'])) {
            return null;
        }

        return $this->productToVariantGroupConverter->convert($item);
    }
}
