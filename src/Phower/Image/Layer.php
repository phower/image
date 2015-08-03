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

    /**
     * Align layer position across image dimensions
     * 
     * @param int $width
     * @param int $height
     * @param int $position
     * @return \Phower\Image\Layer
     * @throws InvalidArgumentException
     */
    public function align($width, $height, $position = self::POSITION_MIDDLE_CENTER)
    {
        if ((int) $width < 1) {
            throw new InvalidArgumentException('Width must be an integer greater than 0.');
        }

        if ((int) $height < 1) {
            throw new InvalidArgumentException('Height must be an integer greater than 0.');
        }

        switch ($position) {
            case self::POSITION_TOP_LEFT :
                $this->posX = 0;
                $this->posY = 0;
                break;
            case self::POSITION_TOP_CENTER :
                $this->posX = (int) round(($width - $this->getWidth()) / 2);
                $this->posY = 0;
                break;
            case self::POSITION_TOP_RIGHT :
                $this->posX = (int) $width - $this->getWidth();
                $this->posY = 0;
                break;
            case self::POSITION_MIDDLE_LEFT :
                $this->posX = 0;
                $this->posY = (int) round(($height - $this->getHeight()) / 2);
                break;
            case self::POSITION_MIDDLE_CENTER :
                $this->posX = (int) round(($width - $this->getWidth()) / 2);
                $this->posY = (int) round(($height - $this->getHeight()) / 2);
                break;
            case self::POSITION_MIDDLE_RIGHT :
                $this->posX = (int) $width - $this->getWidth();
                $this->posY = (int) round(($height - $this->getHeight()) / 2);
                break;
            case self::POSITION_BOTTOM_LEFT :
                $this->posX = 0;
                $this->posY = (int) $height - $this->getHeight();
                break;
            case self::POSITION_BOTTOM_CENTER :
                $this->posX = (int) round(($width - $this->getWidth()) / 2);
                $this->posY = (int) $height - $this->getHeight();
                break;
            case self::POSITION_BOTTOM_RIGHT :
                $this->posX = (int) $width - $this->getWidth();
                $this->posY = (int) $height - $this->getHeight();
                break;
            default:
                throw new InvalidArgumentException('Position must be an integer between 1 and 9.');
        }

        return $this;
    }

    /**
     * Resize this layer to the given new dimensions and adjust itsposition
     * if new coordinates are supplied
     * 
     * @param int $width
     * @param int $height
     * @param int|null $posX
     * @param int|null $posY
     * @return \Phower\Image\Layer
     */
    public function resize($width, $height, $posX = null, $posY = null)
    {
        if ($posX === null) {
            $posX = $this->getPosX();
        }

        if ($posY === null) {
            $posY = $this->getPosY();
        }

        $this->getAdapter()->resize($width, $height);

        $this->setPosX($posX)->setPosY($posY);

        return $this;
    }

}
