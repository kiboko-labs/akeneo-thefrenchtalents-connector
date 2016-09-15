<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter\Attribute;

use Kiboko\Component\TheFrenchTalentsConnector\Downloader\MediaDownloader;
use Pim\Component\Connector\ArrayConverter\StandardArrayConverterInterface;

class MediaConverter
    implements StandardArrayConverterInterface
{
    /**
     * @var MediaDownloader
     */
    private $downloader;

    /**
     * ColorConverter constructor.
     * @param MediaDownloader $downloader
     */
    public function __construct(
        MediaDownloader $downloader
    ) {
        $this->downloader = $downloader;
    }

    /**
     * @param array $item
     * @param array $options
     * @return array
     */
    public function convert(array $item, array $options = [])
    {
        if (isset($options['mediaDownload']) && $options['mediaDownload'] !== true) {
            return [];
        }

        $urls = [
            $item['Image_1'],
            $item['Image_2'],
            $item['Image_3'],
            $item['Image_4'],
            $item['Image_5'],
            $item['Image_6'],
        ];

        $destinationPath = isset($options['mediaPath']) ? $options['mediaPath'] : (sys_get_temp_dir() . '/pim/media');

        $files = $this->downloader->fetch(array_filter($urls, function($item){
            return !empty($item);
        }), $destinationPath);

        $fields = [];
        foreach ($files as $index => $file) {
            $fields[sprintf('image_gallery_%02d', $index + 1)] = $file;
        }

        if (count($files) > 0) {
            reset($files);
            $fields['thumbnail'] = $fields['image'] = current($files);
        }
        $fields['legend-fr_FR'] = $item['Title'];
        $fields['legend-en_GB'] = $item['English_Product_Name'];
        $fields['legend-en_US'] = $item['English_Product_Name'];

        return $fields;
    }
}
