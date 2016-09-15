<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Processor;

use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\ItemProcessorInterface;
use Akeneo\Component\Batch\Step\StepExecutionAwareInterface;
use Doctrine\Common\Collections\Collection;
use Kiboko\Component\Connector\ConfigurationAwareTrait;
use Kiboko\Component\Connector\NameAwareTrait;
use Kiboko\Component\Connector\StepExecutionAwareTrait;
use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class CleanerProcessor
    extends AbstractConfigurableStepElement
    implements ItemProcessorInterface, StepExecutionAwareInterface
{
    use StepExecutionAwareTrait;
    use ConfigurationAwareTrait;
    use NameAwareTrait;

    /**
     * @var StandardArrayConverterInterface
     */
    private $arrayConverter;

    /**
     * @var Collection
     */
    private $skuCache;

    /**
     * @var string
     */
    private $mediaFilePath;

    /**
     * @var string
     */
    private $mediaDownload;

    /**
     * CleanerProcessor constructor.
     *
     * @param StandardArrayConverterInterface $arrayConverter
     * @param Collection $skuCache
     */
    public function __construct(
        StandardArrayConverterInterface $arrayConverter,
        Collection $skuCache
    ) {
        $this->arrayConverter = $arrayConverter;
        $this->skuCache = $skuCache;
    }

    /**
     * @return array
     */
    public function getConfigurationFields()
    {
        return [
            'mediaDownload' => [
                'type'    => 'switch',
                'options' => [
                    'label' => 'luni_tft_connector.steps.reader.mediaDownload.label',
                    'help'  => 'luni_tft_connector.steps.reader.mediaDownload.help',
                ],
            ],
            'mediaFilePath' => [
                'options' => [
                    'label' => 'luni_tft_connector.steps.reader.mediaFilePath.label',
                    'help'  => 'luni_tft_connector.steps.reader.mediaFilePath.help',
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function getMediaFilePath()
    {
        return $this->mediaFilePath;
    }

    /**
     * @param string $mediaFilePath
     */
    public function setMediaFilePath($mediaFilePath)
    {
        $this->mediaFilePath = $mediaFilePath;
    }

    /**
     * @return string
     */
    public function getMediaDownload()
    {
        return $this->mediaDownload;
    }

    /**
     * @param string $mediaDownload
     */
    public function setMediaDownload($mediaDownload)
    {
        $this->mediaDownload = $mediaDownload;
    }

    /**
     * {@inheritdoc}
     */
    public function process($item)
    {
        if (isset($item['Parent_ID']) && !empty($item['Parent_ID'])) {
            $this->skuCache->set(sprintf('TFT%s', $item['Unique_ID']), sprintf('TFT%s', $item['Parent_ID']));
        }

        $item = $this->arrayConverter->convert($item, [
            'mediaPath'     => $this->getMediaFilePath(),
            'mediaDownload' => $this->getMediaDownload(),
        ]);

        return $item;
    }
}
