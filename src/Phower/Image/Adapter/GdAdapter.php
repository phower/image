<?php

namespace Phower\Image\Adapter;

use Phower\Image\Image;
use Phower\Image\Layer;
use Phower\Image\Exception\InvalidArgumentException;
use Phower\Image\Exception\RuntimeException;

class GdAdapter implements AdapterInterface
{

    const VERSION_REQUIRED = '2.0.18';

    /**
     * @var resource
     */
    protected $resource;

    /**
     * Construct
     * 
     * @param resource $resource
     * @throws InvalidArgumentException
     */
    public function __construct($resource)
    {
        if (!self::isInstalled()) {
            throw new RuntimeException('GD library not installed.');
        }

        if (!version_compare(self::getVersion(), self::VERSION_REQUIRED, '>=')) {
            throw new RuntimeException(sprintf('GD library is installed but version is lower than required (>= %s).', self::VERSION_REQUIRED));
        }

        if (!is_resource($resource) || get_resource_type($resource) !== 'gd') {
            throw new InvalidArgumentException('Argument is not a valid GD resource.');
        }

        $this->resource = $resource;
    }

    /**
     * Check if GD is installed
     * 
     * @return boolean
     */
    public static function isInstalled()
    {
        return function_exists('gd_info');
    }

    /**
     * Get GD installed version
     * 
     * @return string|null
     */
    public static function getVersion()
    {
        $info = gd_info();
        $version = isset($info['GD Version']) ? $info['GD Version'] : null;
        return $version;
    }

    /**
     * Create new adapter instance from file
     * 
     * @param string $file
     * @return \Phower\Image\Adapter\GdAdapter
     * @throws InvalidArgumentException
     */
    public static function fromFile($file)
    {
        if (!$string = @file_get_contents($file)) {
            throw new InvalidArgumentException('Unable to read file: ' . $file);
        }

        return self::fromString($string);
    }

    /**
     * Create a new adapter instance from string
     * 
     * @param string $string
     * @return \Phower\Image\Adapter\GdAdapter
     * @throws InvalidArgumentException
     */
    public static function fromString($string)
    {
        if (!$resource = imagecreatefromstring($string)) {
            throw new InvalidArgumentException('Unable to create an image from string, using GD.');
        }

        return new static($resource);
    }

    /**
     * Create a new adapter instance from a new empty image
     * 
     * @param int $width
     * @param int $height
     * @return \Phower\Image\Adapter\GdAdapter
     * @throws InvalidArgumentException
     */
    public static function create($width, $height)
    {
        if ((int) $width < 1) {
            throw new InvalidArgumentException('Width must be greater than 0.');
        }

        if ((int) $height < 1) {
            throw new InvalidArgumentException('Height must be greater than 0.');
        }

        $resource = imagecreatetruecolor($width, $height);

        return new static($resource);
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
     * Get width
     * 
     * @return int
     */
    public function getWidth()
    {
        return imagesx($this->resource);
    }

    /**
     * Get height
     * 
     * @return int
     */
    public function getHeight()
    {
        return imagesy($this->resource);
    }

}
