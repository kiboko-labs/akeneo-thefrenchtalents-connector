<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\Downloader;

use Akeneo\Component\Batch\Item\InvalidItemException;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise\RejectionException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class MediaDownloader
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $temporaryPath;

    /**
     * @var string
     */
    private $trimImages;

    /**
     * MediaDownloader constructor.
     * @param bool $trimImages
     */
    public function __construct(
        $trimImages = true
    ) {
        $this->client = new Client();

        $this->temporaryPath = sys_get_temp_dir();
        $this->trimImages = (bool) $trimImages;
    }

    /**
     * @param array $urls
     * @param string $destinationPath
     * @return array
     */
    public function fetch(array $urls, $destinationPath)
    {
        $requests = [];
        $temporary = [];
        foreach ($urls as $index => $imagePath) {
            $filename = $this->prepareImageLocalPath(basename($imagePath), $destinationPath);

            if (file_exists($destinationPath . '/' . $filename)) {
                $temporary[$index] = $filename;
                continue;
            }
            $requests[$index] = $this->prepareImageRequest(
                $imagePath,
                $temporary[$index] = $filename
            );
        }

        try {
            $pool = new Pool($this->client, $requests, [
                'concurrency' => 8,
                'fulfilled' => function (Response $response, $index) use ($temporary, $destinationPath) {
                    $stream = $response->getBody();
                    while (!$stream->eof()) {
                        file_put_contents($destinationPath . '/' . $temporary[$index], $stream->read(1000), FILE_APPEND);
                    }

                    $image = new \Imagick($destinationPath . '/' . $temporary[$index]);
                    $image->setBackgroundColor(new \ImagickPixel('#fff'));
                    $image->trimImage(.3);
                    $image->writeImage($destinationPath . '/' . $temporary[$index]);
                },
            ]);
            $pool->promise()->wait();
        } catch (RejectionException $e) {
            return [];
        }

        $images = [];
        foreach ($temporary as $index => $path) {
            if (filesize($destinationPath . '/' . $path) <= 0) {
                continue;
            }

            $images[] = 'media/' . $path;
        }

        return $images;
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

    private function prepareImageLocalPath($filename, $destinationPath)
    {
        $basename = substr($filename, 0, strrpos($filename, '.'));
        $finalPath = '';

        if (strlen($basename) > 2) {
            $finalPath = $basename[0]
                . '/' . $basename[1] . $basename[2]
                . '/' . $basename[3] . $basename[4];
            if (!file_exists(($destinationPath . '/' . $finalPath))) {
                mkdir($destinationPath . '/' . $finalPath, 0755, true);
            }
        }

        return $finalPath . '/' . $filename;
    }
}
