<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Writer;

use Akeneo\Component\Batch\Model\StepExecution;
use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\ItemWriterInterface;
use Akeneo\Component\Batch\Step\StepExecutionAwareInterface;
use Kiboko\Component\Connector\ConfigurationAwareTrait;
use Kiboko\Component\Connector\NameAwareTrait;
use Pim\Bundle\BaseConnectorBundle\Writer\File\CsvWriter;
use Symfony\Component\PropertyAccess\PropertyAccess;

class VariantGroupWriter
    extends AbstractConfigurableStepElement
    implements ItemWriterInterface, StepExecutionAwareInterface
{
    use ConfigurationAwareTrait;
    use NameAwareTrait;

    private $writer;

    public function __construct(CsvWriter $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @return array
     */
    public function getConfigurationFields()
    {
        return [
            'variantGroupFilePath' => [
                'options' => [
                    'label' => 'luni_tft_connector.steps.reader.variantGroupFilePath.label',
                    'help'  => 'luni_tft_connector.steps.reader.variantGroupFilePath.help'
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
            'variantGroupFilePath' => $this->writer->getFilePath()
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
            'filePath'      => isset($config['variantGroupFilePath']) ? $config['variantGroupFilePath'] : null,
            'delimiter'     => ';',
            'enclosure'     => '"',
            'escape'        => '"',
            'withHeader'    => true,
        ]);
    }

    public function getVariantGroupFilePath()
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        return $accessor->getValue($this->writer, 'filePath');
    }

    public function setVariantGroupFilePath($filePath)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($this->writer, 'filePath', $filePath);
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $items)
    {
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
