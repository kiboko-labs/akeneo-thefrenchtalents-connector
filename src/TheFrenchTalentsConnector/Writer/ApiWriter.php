<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Writer;

use Akeneo\Component\Batch\Model\StepExecution;
use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\ItemWriterInterface;
use Akeneo\Component\Batch\Step\StepExecutionAwareInterface;
use Akeneo\Component\StorageUtils\Updater\ObjectUpdaterInterface;
use Kiboko\Component\Connector\ConfigurationAwareTrait;
use Kiboko\Component\Connector\NameAwareTrait;
use Pim\Bundle\BaseConnectorBundle\Writer\File\CsvWriter;
use Pim\Component\Catalog\Model\ProductInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ApiWriter
    extends AbstractConfigurableStepElement
    implements ItemWriterInterface, StepExecutionAwareInterface
{
    use ConfigurationAwareTrait;
    use NameAwareTrait;

    /**
     * @var CsvWriter
     */
    private $writer;

    /**
     * @var ProductExclusionRepository
     */
    private $repository;

    /**
     * @var ObjectUpdaterInterface
     */
    private $productWriter;

    public function __construct(
        CsvWriter $writer/*,
        ProductExclusionRepository $repository,
        ObjectUpdaterInterface $productWriter*/
    ) {
        $this->writer = $writer;
        //$this->repository = $repository;
        //$this->productWriter = $productWriter;
    }

    /**
     * @return array
     */
    public function getConfigurationFields()
    {
        return [
            'apiFilePath' => [
                'options' => [
                    'label' => 'luni_tft_connector.steps.reader.apiFilePath.label',
                    'help'  => 'luni_tft_connector.steps.reader.apiFilePath.help'
                ],
            ],
        ];
    }

    /**
     * Get the step element configuration (based on its properties)
     *
     * @return array
     */
    public function getConfiguration()
    {
        return [
            'apiFilePath' => $this->writer->getFilePath()
        ];
    }

    /**
     * Set the step element configuration
     *
     * @param array $config
     */
    public function setConfiguration(array $config)
    {
        $this->writer->setConfiguration([
            'filePath'      => isset($config['apiFilePath']) ? $config['apiFilePath'] : null,
            'delimiter'     => ',',
            'enclosure'     => '"',
            'escape'        => '"',
            'withHeader'    => true,
        ]);
    }

    public function getApiFilePath()
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        return $accessor->getValue($this->writer, 'filePath');
    }

    public function setApiFilePath($filePath)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($this->writer, 'filePath', $filePath);
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $items)
    {
//        $identifierList = [];
//        /** @var ProductInterface $item */
//        foreach ($items as $item) {
//            $identifierList[] = $item->getIdentifier();
//        }
//
//        foreach ($this->repository->findAllByMissingIdentifier($identifierList) as $product) {
//
//        }

        $this->writer->write($items);
    }

    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        parent::initialize();

        $this->writer->initialize();
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        parent::flush();

        $this->writer->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->writer->setStepExecution($stepExecution);
    }
}
