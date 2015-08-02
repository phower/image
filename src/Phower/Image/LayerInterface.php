<?php

namespace Phower\Image;

interface LayerInterface
{

    const POSITION_TOP_LEFT = 1;
    const POSITION_TOP_CENTER = 2;
    const POSITION_TOP_RIGHT = 3;
    const POSITION_MIDDLE_LEFT = 4;
    const POSITION_MIDDLE_CENTER = 5;
    const POSITION_MIDDLE_RIGHT = 6;
    const POSITION_BOTTOM_LEFT = 7;
    const POSITION_BOTTOM_CENTER = 8;
    const POSITION_BOTTOM_RIGHT = 9;

    /**
     * Get adapter
     * 
     * @return \Phower\Image\Adapter\AdapterInterface
     */
    public function getAdapter();

    /**
     * Set name
     * 
     * @param string $name
     * @return self
     */
    public function setName($name);

    /**
     * get name
     * 
     * @return string
     */
    public function getName();

    /**
     * Set position x coordinate
     * 
     * @param int $posX
     * @return self
     */
    public function setPosX($posX);

    /**
     * Get position x coordinate
     * 
     * @return int
     */
    public function getPosX();

    /**
     * Set position y coordinate
     * 
     * @param int $posY
     * @return self
     */
    public function setPosY($posY);

    /**
     * Get position y coordinate
     * 
     * @return int
     */
    public function getPosY();

    /**
     * Get width
     * 
     * @return int
     */
    public function getWidth();

    /**
     * Get height
     * 
     * @return int
     */
    public function getHeight();

    /**
     * Align layer position across image dimensions
     * 
     * @param int $width
     * @param int $height
     * @param int $position
     * @return self
     * @throws InvalidArgumentException
     */
    public function align($width, $height, $position = self::POSITION_MIDDLE_CENTER);
}
