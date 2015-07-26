<?php

namespace Phower\Image;

use Imagick;
use Phower\Image\Exception\InvalidArgumentException;

class Layer implements LayerInterface
{

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $posX;

    /**
     * @var int
     */
    protected $posY;

    public function __construct($resource, $posX = 0, $posY = 0, $name = null)
    {
        if (!(is_resource($resource) && get_resource_type($resource) === 'gd' ||
                $resource instanceof Imagick)) {
            throw new InvalidArgumentException('Resource must be a GD resource or'
            . ' an instance of Imagick or Gmagick.');
        }

        $this->resource = $resource;
        $this->posX = (int) $posX;
        $this->posY = (int) $posY;

        if ($name) {
            $this->name = (string) $name;
        }
    }

    /**
     * Get resource
     * 
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set name
     * 
     * @param string $name
     * @return \Phower\Image\Layer
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * get name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set position x coordinate
     * 
     * @param int $posX
     * @return \Phower\Image\Layer
     */
    public function setPosX($posX)
    {
        $this->posX = (int) $posX;
        return $this;
    }

    /**
     * Get position x coordinate
     * 
     * @return int
     */
    public function getPosX()
    {
        return $this->posX;
    }

    /**
     * Set position y coordinate
     * 
     * @param int $posY
     * @return \Phower\Image\Layer
     */
    public function setPosY($posY)
    {
        $this->posY = (int) $posY;
        return $this;
    }

    /**
     * Get position y coordinate
     * 
     * @return int
     */
    public function getPosY()
    {
        return $this->posY;
    }

}
