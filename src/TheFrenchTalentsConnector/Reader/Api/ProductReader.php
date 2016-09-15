<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Reader\Api;

use Kiboko\Component\Connector\Adapter\AdapterInterface;
use Kiboko\Component\TheFrenchTalentsConnector\Manager\SupplierManager;

class ProductReader
    extends PagedReader
{
    /**
     * @var int
     */
    public $brandId;

    /**
     * @var SupplierManager
     */
    private $suppliersManager;

    /**
     * ProductReader constructor.
     * @param AdapterInterface $adapter
     * @param SupplierManager $supplierManager
     */
    public function __construct(AdapterInterface $adapter, SupplierManager $supplierManager)
    {
        parent::__construct($adapter);

        $this->suppliersManager = $supplierManager;
    }


    /**
     * @return array
     */
    protected function getQueryParameters()
    {
        return array_merge(
            parent::getQueryParameters(),
            [
                'brand_id'  => (int) $this->brandId,
            ]
        );
    }

    /**
     * @return array
     */
    public function getConfigurationFields()
    {
        return array_merge(
            parent::getConfigurationFields(),
            [
                'brandId' => [
                    'type'    => 'choice',
                    'options' => [
                        'choices'  => $this->suppliersManager->getChoices(),
                        'required' => true,
                        'select2'  => true,
                        'label' => 'luni_tft_connector.steps.reader.brandId.label',
                        'help'  => 'luni_tft_connector.steps.reader.brandId.help',
                    ],
                ],
            ]
        );
    }
}
