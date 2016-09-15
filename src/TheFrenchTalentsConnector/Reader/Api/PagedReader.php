<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Reader\Api;

use Akeneo\Component\Batch\Model\StepExecution;
use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\ItemReaderInterface;
use Akeneo\Component\Batch\Job\RuntimeErrorException;
use Akeneo\Component\Batch\Step\StepExecutionAwareInterface;
use Kiboko\Component\Connector\Adapter\AdapterInterface;

class PagedReader
    extends AbstractConfigurableStepElement
    implements ItemReaderInterface, StepExecutionAwareInterface
{
    /**
     * @var StepExecution
     */
    protected $stepExecution;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var \Iterator
     */
    private $iterator;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    public $pageSize;

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
    public $pageStart;

    /**
     * @var int|null
     */
    public $pageEnd;

    /**
     * @var int
     */
    public $offset;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->client = new \GuzzleHttp\Client();
        $this->adapter = $adapter;

        $this->pageSize = $this->pageSize ?: 1000;
        $this->pageSize = (int) min(1000, max(0, $this->pageSize));
    }

    protected function getQueryParameters()
    {
        return [
            'api_key'   => $this->apiKey,
            'page_size' => $this->pageSize,
        ];
    }

    public function loadPage($pageId = null)
    {
        if ($pageId === null) {
            $pageId = $this->currentPage;
        }

        if ($this->pageEnd !== null && $pageId > $this->pageEnd) {
            return;
        }
        $this->offset = $this->pageSize * ($pageId - 1);

        $response = $this->client->request('GET', $this->apiPath, [
            'query' => array_merge($this->getQueryParameters(), ['page_no' => $pageId]),
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeErrorException(sprintf('API response returned code %d (%s)',
                $response->getStatusCode(), $response->getReasonPhrase()));
        }

        $this->adapter->loadFromString($response->getBody());
        $this->stepExecution->incrementSummaryInfo('page');
        $this->iterator = $this->adapter->getIterator();

        ++$this->currentPage;
    }

    public function initialize()
    {
        $this->currentPage = $this->pageStart ?: 1;
        $this->loadPage();
    }

    public function read()
    {
        if ($this->iterator->key() + $this->offset >= $this->adapter->size()) {
            return null;
        }

        if (!$this->iterator->valid()) {
            $this->loadPage();
        }

        if (!$this->iterator->valid()) {
            return null;
        }

        $data = $this->iterator->current();
        $this->iterator->next();

        if ($data !== null) {
            $this->stepExecution->incrementSummaryInfo('item');
        }

        return $data;
    }

    public function getConfigurationFields()
    {
        return [
            'apiPath' => [
                'type'    => 'url',
                'options' => [
                    'label'    => 'luni_tft_connector.steps.reader.apiPath.label',
                    'help'     => 'luni_tft_connector.steps.reader.apiPath.help',
                    'required' => true,
                ],
            ],
            'apiKey' => [
                'options' => [
                    'label'    => 'luni_tft_connector.steps.reader.apiKey.label',
                    'help'     => 'luni_tft_connector.steps.reader.apiKey.help',
                    'required' => true,
                ],
            ],
            'pageSize' => [
                'type'    => 'integer',
                'options' => [
                    'label'    => 'luni_tft_connector.steps.reader.pageSize.label',
                    'help'     => 'luni_tft_connector.steps.reader.pageSize.help',
                    'required' => true,
                ],
            ],
            'pageStart' => [
                'type'    => 'integer',
                'options' => [
                    'label' => 'luni_tft_connector.steps.reader.pageStart.label',
                    'help'  => 'luni_tft_connector.steps.reader.pageStart.help',
                    'subblock' => 'API',
                ],
            ],
            'pageEnd' => [
                'type'    => 'integer',
                'options' => [
                    'label' => 'luni_tft_connector.steps.reader.pageEnd.label',
                    'help'  => 'luni_tft_connector.steps.reader.pageEnd.help',
                    'scale'    => 0
                ],
            ],
        ];
    }

    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->stepExecution = $stepExecution;
    }
}
