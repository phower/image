<?php

namespace Phower\Image\Adapter;

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
    public static function isInstalled();

    /**
     * Get installed library version
     * 
     * @return string|null
     */
    public static function getVersion();

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
     * @return \Phower\Image\Adapter\AdapterInterface
     * @throws InvalidArgumentException
     */
    public static function create($width, $height);

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
}
