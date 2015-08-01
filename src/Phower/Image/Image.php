<?php

namespace Phower\Image;

use Phower\Image\Adapter\AdapterInterface;
use Phower\Image\Exception\InvalidArgumentException;
use Phower\Image\Exception\RuntimeException;

class Image implements ImageInterface
{

    /**
     * @var array
     */
    protected $adapterAlias = [
        self::ADAPTER_GD => 'Phower\Image\Adapter\GdAdapter',
        self::ADAPTER_GMAGICK => 'Phower\Image\Adapter\GmagickAdapter',
        self::ADAPTER_IMAGICK => 'Phower\Image\Adapter\ImagickAdapter',
    ];

    /**
     * @var string
     */
    protected $defaultAdapter;

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

    /**
     * Construct new image
     * 
     * @param \Phower\Image\Adapter\AdapterInterface|strin|null $defaultAdapter
     * @param int|null $width
     * @param int|null $height
     * @param \Phower\Image\ColorInterface|null $backgroundColor
     */
    public function __construct($defaultAdapter = null, $width = null, $height = null)
    {
        if ($defaultAdapter !== null) {
            if (is_string($defaultAdapter) && isset($this->adapterAlias[$defaultAdapter])) {
                $this->setDefaultAdapter($this->adapterAlias[$defaultAdapter]);
            } else {
                $this->setDefaultAdapter($defaultAdapter);
            }
        }

        if ($width !== null) {
            $this->setWidth($width);
        }

        if ($height !== null) {
            $this->setHeight($height);
        }

        $this->layers = new LayersStack();
    }

    /**
     * Set default adapter
     * 
     * @param string|Phower\Image\Adapter\AdapterInterface $defaultAdapter
     * @return \Phower\Image\Image
     * @throws InvalidArgumentException
     */
    public function setDefaultAdapter($defaultAdapter)
    {
        if (!is_a($defaultAdapter, 'Phower\Image\Adapter\AdapterInterface', true)) {
            throw new InvalidArgumentException('Default adapter must be an '
            . 'instance or the name of a class which implements '
            . '"Phower\Image\Adapter\AdapterInterface"');
        }

        $this->defaultAdapter = is_object($defaultAdapter) ?
                get_class($defaultAdapter) : $defaultAdapter;

        return $this;
    }

    /**
     * Get default adapter
     * 
     * @return string
     */
    public function getDefaultAdapter()
    {
        if ($this->defaultAdapter === null) {
            if (class_exists('Imagick')) {
                $this->defaultAdapter = $this->adapterAlias[self::ADAPTER_IMAGICK];
            } elseif (function_exists('gd_info')) {
                $this->defaultAdapter = $this->adapterAlias[self::ADAPTER_GD];
            } elseif (class_exists('Gmagick')) {
                $this->defaultAdapter = $this->adapterAlias[self::ADAPTER_GMAGICK];
            } else {
                throw new RuntimeException('At least one of Imagick, Gmagick or GD must be installed. '
                . 'None of them was found.');
            }
        }
        return $this->defaultAdapter;
    }

    /**
     * Set width
     * 
     * @param int $width
     * @return \Phower\Image\Image
     * @throws InvalidArgumentException
     */
    public function setWidth($width)
    {
        if ((int) $width < 1) {
            throw new InvalidArgumentException('Height must be greater than zero.');
        }
        $this->width = (int) $width;
        return $this;
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
     * Set height
     * 
     * @param int $height
     * @return \Phower\Image\Image
     * @throws InvalidArgumentException
     */
    public function setHeight($height)
    {
        if ((int) $height < 1) {
            throw new InvalidArgumentException('Height must be greater than zero.');
        }
        $this->height = (int) $height;
        return $this;
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

    /**
     * Import file into a new layer
     * 
     * @param string $file
     * @param int $mode
     * @return \Phower\Image\Image
     * @throws InvalidArgumentException
     */
    public function import($file, $mode = LayersStack::APPEND_TOP)
    {
        if (!is_readable($file)) {
            throw new InvalidArgumentException('Unable to import source: ' . $file);
        }

        /* @var $adapter \Phower\Image\Adapter\AdapterInterface */
        $callback = $this->getDefaultAdapter() . '::fromFile';
        $adapter = call_user_func_array($callback, [$file]);
        $layer = new Layer($adapter);

        if ($this->width === null && $this->height === null && $this->layers->count() === 0) {
            $this->width = $layer->getWidth();
            $this->height = $layer->getHeight();
        }

        $this->layers->append($layer, $mode);

        return $this;
    }

}
