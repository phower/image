<?php

namespace Phower\Image\Adapter;

use Imagick;
use Phower\Image\Image;
use Phower\Image\Layer;
use Phower\Image\Color;
use Phower\Image\ColorInterface;
use Phower\Image\Exception\InvalidArgumentException;
use Phower\Image\Exception\RuntimeException;

class ImagickAdapter implements AdapterInterface
{

    const VERSION_REQUIRED = '6.7.7';

    /**
     * @var resource
     */
    protected $resource;

    /**
     * Construct
     * 
     * @param \Imagick $resource
     * @throws InvalidArgumentException
     */
    public function __construct($resource)
    {
        if (!$this->isInstalled()) {
            throw new RuntimeException('Imagick library not installed.');
        }

        if (!$resource instanceof Imagick) {
            throw new InvalidArgumentException('Argument is not an instance of Imagick.');
        }

        $this->resource = $resource;

        if (!version_compare($this->getVersion(), self::VERSION_REQUIRED, '>=')) {
            throw new RuntimeException(sprintf('Imagick library is installed but version is lower than required (>= %s).', self::VERSION_REQUIRED));
        }
    }

    /**
     * Check if Imagick is installed
     * 
     * @return boolean
     */
    public function isInstalled()
    {
        return extension_loaded('imagick') && class_exists('Imagick');
    }

    /**
     * Get Imagick installed version
     * 
     * @return string|null
     */
    public function getVersion()
    {
        $info = $this->resource->getVersion();
        $version = isset($info['versionString']) ? substr($info['versionString'], 12) : null;

        return $version;
    }

    /**
     * Create new adapter instance from file
     * 
     * @param string $file
     * @return \Phower\Image\Adapter\ImagickAdapter
     * @throws InvalidArgumentException
     */
    public static function fromFile($file)
    {
        if (!is_readable($file)) {
            throw new InvalidArgumentException('Unable to read from ' . $file);
        }

        $resource = new Imagick($file);

        return new static($resource);
    }

    /**
     * Create a new adapter instance from string
     * 
     * @param string $string
     * @return \Phower\Image\Adapter\ImagickAdapter
     * @throws RuntimeException
     */
    public static function fromString($string)
    {
        $file = tempnam(sys_get_temp_dir(), uniqid('image'));

        if (false === file_put_contents($file, $string)) {
            throw new RuntimeException('Unable to create a temporary file.');
        }

        $resource = new Imagick($file);
        unlink($file);

        return new static($resource);
    }

    /**
     * Create a new adapter instance from a new empty image
     * 
     * @param int $width
     * @param int $height
     * @return \Phower\Image\Adapter\ImagickAdapter
     * @throws InvalidArgumentException
     */
    public static function create($width, $height, ColorInterface $background = null)
    {
        if ((int) $width < 1) {
            throw new InvalidArgumentException('Width must be greater than 0.');
        }

        if ((int) $height < 1) {
            throw new InvalidArgumentException('Height must be greater than 0.');
        }

        if ($background === null) {
            $background = new Color();
        }
        
        $resource = new Imagick();
        $resource->newimage($width, $height, $background->toRgba());

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
        return $this->resource->getimagewidth();
    }

    /**
     * Get height
     * 
     * @return int
     */
    public function getHeight()
    {
        return $this->resource->getimageheight();
    }

}
