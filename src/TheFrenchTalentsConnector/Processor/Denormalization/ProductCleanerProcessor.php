<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Processor\Denormalization;

use Akeneo\Component\Batch\Model\StepExecution;
use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\ItemProcessorInterface;
use Akeneo\Component\Batch\Step\StepExecutionAwareInterface;
use Akeneo\Component\StorageUtils\Detacher\ObjectDetacherInterface;
use Kiboko\Component\Connector\ConfigurationAwareTrait;
use Kiboko\Component\Connector\NameAwareTrait;
use Pim\Component\Catalog\Model\ProductInterface;
use Pim\Bundle\CatalogBundle\Repository\ProductRepositoryInterface;
use Pim\Component\Connector\Processor\Denormalization\ProductProcessor;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

class ProductCleanerProcessor
    extends AbstractConfigurableStepElement
    implements ItemProcessorInterface, StepExecutionAwareInterface
{
    use ConfigurationAwareTrait;
    use NameAwareTrait;

    /**
     * @var ProductProcessor
     */
    private $decorated;

    /**
     * @var ProductRepositoryInterface
     */
    private $repository;

    /**
     * @var StepExecution
     */
    private $stepExecution;

    /**
     * @var ObjectDetacherInterface
     */
    private $detacher;

    /**
     * VariantFilterProcessor constructor.
     *
     * @param ProductProcessor $decorated
     * @param ProductRepositoryInterface $repository
     * @param ObjectDetacherInterface $detacher
     */
    public function __construct(
        ProductProcessor $decorated,
        ProductRepositoryInterface $repository,
        ObjectDetacherInterface $detacher
    ) {
        $this->decorated = $decorated;
        $this->repository = $repository;
        $this->detacher = $detacher;
    }

    /**
     * @return array
     */
    public function getConfigurationFields()
    {
        return $this->decorated->getConfigurationFields();
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->decorated->setEnabled($enabled);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->decorated->isEnabled();
    }

    /**
     * @param string $categoriesColumn
     */
    public function setCategoriesColumn($categoriesColumn)
    {
        $this->decorated->setCategoriesColumn($categoriesColumn);
    }

    /**
     * @return string
     */
    public function getCategoriesColumn()
    {
        return $this->decorated->getCategoriesColumn();
    }

    /**
     * @param string $groupsColumn
     */
    public function setGroupsColumn($groupsColumn)
    {
        $this->decorated->setGroupsColumn($groupsColumn);
    }

    /**
     * @return string
     */
    public function getGroupsColumn()
    {
        return $this->decorated->getGroupsColumn();
    }

    /**
     * @param string $familyColumn
     */
    public function setFamilyColumn($familyColumn)
    {
        $this->decorated->setFamilyColumn($familyColumn);
    }

    /**
     * @return string
     */
    public function getFamilyColumn()
    {
        return $this->decorated->getFamilyColumn();
    }

    /**
     * @param bool $enabledComparison
     */
    public function setEnabledComparison($enabledComparison)
    {
        $this->decorated->setEnabledComparison($enabledComparison);
    }

    /**
     * @return bool
     */
    public function isEnabledComparison()
    {
        return $this->decorated->isEnabledComparison();
    }

    /**
     * Set the separator for decimal
     *
     * @param string $decimalSeparator
     */
    public function setDecimalSeparator($decimalSeparator)
    {
        $this->decorated->setDecimalSeparator($decimalSeparator);
    }

    /**
     * Get the delimiter for decimal
     *
     * @return string
     */
    public function getDecimalSeparator()
    {
        return $this->decorated->getDecimalSeparator();
    }

    /**
     * Set the format for date field
     *
     * @param string $dateFormat
     */
    public function setDateFormat($dateFormat)
    {
        $this->decorated->setDateFormat($dateFormat);
    }

    /**
     * Get the format for the date field
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->decorated->getDateFormat();
    }

    /**
     * @param StepExecution $stepExecution
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->stepExecution = $stepExecution;
        $this->decorated->setStepExecution($stepExecution);
    }

    /**
     * @param mixed $item
     * @return null|ProductInterface
     */
    public function process($item)
    {
        $product = $this->decorated->process($item);

        if ($product instanceof ProductInterface && $product->getId()) {
            $this->detacher->detach($product);
            return null;
        }

        return $product;
    }
}
