<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Reader;

use Akeneo\Component\Batch\Model\StepExecution;
use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\ItemReaderInterface;
use Akeneo\Component\Batch\Step\StepExecutionAwareInterface;
use Kiboko\Component\Connector\ConfigurationAwareTrait;
use Kiboko\Component\Connector\NameAwareTrait;
use Pim\Component\Connector\Reader\File\CsvReader;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ProductReader
    extends AbstractConfigurableStepElement
    implements ItemReaderInterface, StepExecutionAwareInterface
{
    use ConfigurationAwareTrait;
    use NameAwareTrait;

    private $reader;

    public function __construct(CsvReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @return array
     */
    public function getConfigurationFields()
    {
        return [
            'productFilePath' => [
                'options' => [
                    'label' => 'luni_tft_connector.steps.reader.productFilePath.label',
                    'help'  => 'luni_tft_connector.steps.reader.productFilePath.help'
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
            'productFilePath' => $this->reader->getFilePath()
        ];
    }

    /**
     * Set the step element configuration
     *
     * @param array $config
     */
    public function setConfiguration(array $config)
    {
        $this->reader->setConfiguration([
            'filePath'      => isset($config['productFilePath']) ? $config['productFilePath'] : null,
            'delimiter'     => ';',
            'enclosure'     => '"',
            'escape'        => '"',
            'uploadAllowed' => false,
        ]);
    }

    public function getProductFilePath()
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        return $accessor->getValue($this->reader, 'filePath');
    }

    public function setProductFilePath($filePath)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($this->reader, 'filePath', $filePath);
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        return $this->reader->read();
    }

    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        parent::initialize();

        $this->reader->initialize();
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        parent::flush();

        $this->reader->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->reader->setStepExecution($stepExecution);
    }
}
