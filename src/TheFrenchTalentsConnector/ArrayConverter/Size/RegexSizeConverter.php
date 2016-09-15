<?php

namespace Kiboko\Component\TheFrenchTalentsConnector\ArrayConverter\Size;

use Kiboko\Component\Connector\Discoverer\AttributeOptionDiscoverer;

class RegexSizeConverter
    extends SizeConverter
{
    /**
     * @var string
     */
    private $regex;

    /**
     * RegexSizeConverter constructor.
     * @param string $attribute
     * @param AttributeOptionDiscoverer $mapper
     * @param string $regex
     */
    public function __construct(
        $attribute,
        AttributeOptionDiscoverer $mapper,
        $regex
    ) {
        parent::__construct($attribute, $mapper);
        $this->regex = $regex;
    }

    /**
     * @param string $size
     * @return array
     */
    protected function convertSize($size)
    {
        if (!preg_match($this->regex, $size, $matches)) {
            return null;
        }

        return parent::convertSize($matches[1]);
    }
}
