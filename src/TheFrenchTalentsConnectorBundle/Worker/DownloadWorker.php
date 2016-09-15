<?php

namespace Kiboko\Bundle\TheFrenchTalentsConnectorBundle\Worker;

use Doctrine\Common\Collections\ArrayCollection;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise\RejectionException;
use GuzzleHttp\Client;

class DownloadWorker
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $concurrency;

    /**
     * @var ArrayCollection
     */
    private $requests;

    /**
     * @var ArrayCollection
     */
    private $downloads;

    /**
     * @param Client $client
     * @param int $concurrency
     */
    public function __construct(Client $client, $concurrency)
    {
        $this->client = $client;
        $this->concurrency = min(64, max(1, $concurrency));

        $this->path = sys_get_temp_dir();
        $this->downloads = new ArrayCollection();
        $this->requests = new ArrayCollection();
    }

    /**
     * @param string $url
     * @param string $temporaryFilename
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    private function prepareImageRequest($url, $temporaryFilename)
    {
        return new Request('GET', $url, [
            'save_to'         => $temporaryFilename,
            'allow_redirects' => false,
            'timeout'         => 5
        ]);
    }

    /**
     * @param string $url
     */
    public function pushDownload($url)
    {
        $this->downloads->add([
            'temporary' => ($temporary = tempnam($this->path, 'tft_')),
            'filename'  => basename($url)
        ]);

        $this->requests->add($this->prepareImageRequest($url, $temporary));
    }

    /**
     * @param int $index
     * @return string
     */
    public function getRequests($index)
    {
        return $this->requests->get($index);
    }

    /**
     * @param int $index
     * @return string
     */
    public function getDownload($index)
    {
        return $this->downloads->get($index);
    }

    /**
     * @param int $index
     * @return string
     */
    public function getTemporary($index)
    {
        if ($download = $this->getDownload($index)) {
            return $download['temporary'];
        }

        return null;
    }

    /**
     * @param int $index
     * @return string
     */
    public function getFilename($index)
    {
        if ($download = $this->getDownload($index)) {
            return $download['filename'];
        }

        return null;
    }

    /**
     * @return \Generator
     */
    public function walkRequests()
    {
        foreach ($this->requests as $index => $file) {
            yield $index => $file;
        }
    }

    /**
     * @return \Generator
     */
    public function walkDownloads()
    {
        foreach ($this->downloads as $index => $download) {
            yield $index => $download;
        }
    }

    /**
     * @return \Generator
     */
    public function walkTemporary()
    {
        foreach ($this->walkDownloads() as $index => $download) {
            yield $index => $download['temporary'];
        }
    }

    /**
     * @return \Generator
     */
    public function walkFilenames()
    {
        foreach ($this->walkDownloads() as $index => $download) {
            yield $index => $download['filename'];
        }
    }

    /**
     * @return \Generator
     */
    public function countRequests()
    {
        return $this->requests->count();
    }

    /**
     * @return \Generator
     */
    public function countDownloads()
    {
        return $this->downloads->count();
    }

    /**
     *
     */
    public function run()
    {
        try {
            $pool = new Pool($this->client, $this->requests->toArray(), [
                'concurrency' => $this->concurrency,
                'fulfilled'   => [$this, 'successCallback']
            ]);
            $pool->promise()->wait();
        } catch (RejectionException $e) {
        }
    }

    /**
     * @param Response $response
     * @param int $index
     */
    public function successCallback(Response $response, $index)
    {
        $stream = $response->getBody();
        while (!$stream->eof()) {
            file_put_contents($this->getTemporary($index), $stream->read(1000), FILE_APPEND);
        }

        return;
        $image = new \Imagick($this->getTemporary($index));
        $image->setBackgroundColor(new \ImagickPixel('#fff'));
        $image->trimImage(.3);
        $image->writeImage($this->getTemporary($index));
    }
}
