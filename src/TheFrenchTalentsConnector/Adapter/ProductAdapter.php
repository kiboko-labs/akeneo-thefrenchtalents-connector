<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Adapter;

use Kiboko\Component\Connector\Adapter\AdapterInterface;
use Kiboko\Component\Connector\Adapter\AdapterTrait;
use Traversable;

class ProductAdapter
    implements AdapterInterface
{
    use AdapterTrait;

    /**
     * @var \DOMNodeList
     */
    private $nodeList;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * @var \DateTime
     */
    private $lastUpdate;

    /**
     * @param \DOMDocument $document
     */
    public function load(\DOMDocument $document)
    {
        $request = new \DOMXPath($document);

        $node = $request->query('/tft_catalog/total_products');
        if ($node->length === 1) {
            $this->totalCount = $node->item(0)->nodeValue;
        } else {
            $this->totalCount = null;
        }

        $node = $request->query('/tft_catalog/last_catalog_update');
        if ($node->length === 1) {
            try {
                $this->lastUpdate = new \DateTime($node->item(0)->nodeValue, new \DateTimeZone('Europe/Paris'));
            } catch (\Exception $e) {
            }
        } else {
            $this->lastUpdate = null;
        }

        $this->nodeList = $request->query('/tft_catalog/products/product');
    }

    public function size()
    {
        return $this->totalCount;
    }

    public function getIterator()
    {
        /** @var \DOMNode $product */
        foreach ($this->nodeList as $product) {
            $item = [];
            /** @var \DOMNode $node */
            foreach ($product->childNodes as $node) {
                $item[$node->nodeName] = $node->nodeValue;
            }
            yield $item;
        }
    }

    public function count()
    {
        return $this->nodeList->length;
    }
}
