<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Reader\Api;

use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\ItemReaderInterface;
use Akeneo\Component\Batch\Job\RuntimeErrorException;
use Symfony\Component\Finder\Adapter\AdapterInterface;

class DefaultReader
    extends AbstractConfigurableStepElement
    implements ItemReaderInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var bool
     */
    private $isLoaded;

    /**
     * @var string
     */
    public $apiPath;

    /**
     * @var string
     */
    public $apiKey;

    /**
     * @var int
     */
    public $brandId;

    /**
     * @param AdapterInterface $adapter
     * @param \GuzzleHttp\Client|null $client
     */
    public function __construct(AdapterInterface $adapter, \GuzzleHttp\Client $client = null)
    {
        if ($client === null) {
            $this->client = new \GuzzleHttp\Client();
        } else {
            $this->client = $client;
        }

        $this->adapter = $adapter;
    }

    public function initialize()
    {
        if ($this->isLoaded === false) {
            $this->load();
        }
    }

    public function load()
    {
        $response = $this->client->request('GET', $this->apiPath, [
            'query' => [
                'api_key'   => $this->apiKey,
                'brand_id'  => $this->brandId,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeErrorException(sprintf('API response returned code %d (%s)',
                $response->getStatusCode(), $response->getReasonPhrase()));
        }

        $this->adapter->loadFromString($response->getBody());
        $this->isLoaded = true;
    }

    public function read()
    {
        if (!$this->adapter->valid() || !$this->isLoaded) {
            throw new \RuntimeException('An unexpected error occured.');
        }

        $data = $this->adapter->current();
        $this->adapter->next();

        return $data;
    }

    public function getConfigurationFields()
    {
        return [
            'brandId' => [
                'options' => [
                    'label' => 'luni_tft_connector.steps.reader.brandId.label',
                    'help'  => 'luni_tft_connector.steps.reader.brandId.help',
                ],
            ],
            'apiPath' => [
                'options' => [
                    'label' => 'luni_tft_connector.steps.reader.apiPath.label',
                    'help'  => 'luni_tft_connector.steps.reader.apiPath.help',
                ],
            ],
            'apiKey' => [
                'options' => [
                    'label' => 'luni_tft_connector.steps.reader.apiKey.label',
                    'help'  => 'luni_tft_connector.steps.reader.apiKey.help',
                ],
            ],
        ];
    }
}
