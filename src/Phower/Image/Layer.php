<?php

namespace Phower\Image;

use Phower\Image\Exception\InvalidArgumentException;
use Phower\Image\Adapter\AdapterInterface;

class Layer implements LayerInterface
{

    /**
     * @var \Phower\Image\Adapter\AdapterInterface
     */
    protected $adapter;

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

    public function __construct(AdapterInterface $adapter, $posX = 0, $posY = 0, $name = null)
    {
        $this->adapter = $adapter;
        $this->posX = (int) $posX;
        $this->posY = (int) $posY;

        if ($name) {
            $this->name = (string) $name;
        }
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

    /**
     * Get width
     * 
     * @return int
     */
    public function getWidth()
    {
        return $this->adapter->getWidth();
    }

    /**
     * Get height
     * 
     * @return int
     */
    public function getHeight()
    {
        return $this->adapter->getHeight();
    }

}
