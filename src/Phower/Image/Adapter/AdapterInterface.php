<?php

namespace Phower\Image\Adapter;

use Phower\Image\ColorInterface;

interface AdapterInterface
{

    /**
     * Construct
     * 
     * @param resource|object $resource
     * @throws InvalidArgumentException
     */
    public function __construct($resource);

    /**
     * Check if required library is installed
     * 
     * @return boolean
     */
    public function isInstalled();

    /**
     * Get installed library version
     * 
     * @return string|null
     */
    public function getVersion();

    /**
     * Create new adapter instance from file
     * 
     * @param string $file
     * @return \Phower\Image\Adapter\AdapterInterface
     * @throws InvalidArgumentException
     */
    public static function fromFile($file);

    /**
     * Create a new adapter instance from string
     * 
     * @param string $string
     * @return \Phower\Image\Adapter\AdapterInterface
     * @throws InvalidArgumentException
     */
    public static function fromString($string);

    /**
     * Create a new adapter instance from a new empty image
     * 
     * @param int $width
     * @param int $height
     * @param \Phower\Image\ColorInterface $background
     * @return \Phower\Image\Adapter\AdapterInterface
     * @throws InvalidArgumentException
     */
    public static function create($width, $height, ColorInterface $background = null);

    /**
     * Get resource
     * 
     * @return resource|object
     */
    public function getResource();

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
     * Resize the image for any given width and height
     * 
     * @param int $width
     * @param int $height
     * @return \Phower\Image\Adapter\AdapterInterface
     */
    public function resize($width, $height);
}
