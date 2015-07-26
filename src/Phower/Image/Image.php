<?php

namespace Phower\Image;

use Phower\Image\Adapter\AdapterInterface;
use Phower\Image\Exception\InvalidArgumentException;

class Image
{

    /**
     * @var \Phower\Image\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var \Phower\Image\LayersStack
     */
    protected $layers;

    public function __construct(AdapterInterface $adapter, $width, $height)
    {
        if ($width < 1) {
            throw new InvalidArgumentException('Image width must be greater than 0.');
        }

        if ($height < 1) {
            throw new InvalidArgumentException('Image height must be greater than 0.');
        }

        $this->adapter = $adapter;
        $this->width = (int) $width;
        $this->height = (int) $height;

        $this->layers = new LayersStack();
    }

    /**
     * Get adapter
     * 
     * @return \Phower\Image\Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Get width
     * 
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Get height
     * 
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Get layers
     * 
     * @return LayersStack
     */
    public function getLayers()
    {
        return $this->layers;
    }

}
