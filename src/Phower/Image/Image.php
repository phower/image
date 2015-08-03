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
            } else {
                throw new RuntimeException('At least one of Imagick or GD must be installed. '
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
    public function import($file, $mode = LayersStack::APPEND_TOP, $position = LayerInterface::POSITION_MIDDLE_CENTER)
    {
        if (!is_readable($file)) {
            throw new InvalidArgumentException('Unable to import source: ' . $file);
        }

        /* @var $adapter \Phower\Image\Adapter\AdapterInterface */
        $callback = $this->getDefaultAdapter() . '::fromFile';
        $adapter = call_user_func_array($callback, [$file]);
        $layer = new Layer($adapter);

        if (($this->width === null || $this->height === null) && $this->layers->count() === 0) {
            $this->width = $layer->getWidth();
            $this->height = $layer->getHeight();
        } else {
            $layer->align($this->width, $this->height, $position);
        }

        $this->layers->append($layer, $mode);

        return $this;
    }

    /**
     * Resize image and all layers to new dimensions and adjust layers'
     * positions proportionally
     * 
     * @param int|null $width
     * @param int|null $height
     * @return \Phower\Image\Image
     * @throws InvalidArgumentException
     */
    public function resize($width = null, $height = null)
    {
        if ($width === null && $height === null) {
            throw new InvalidArgumentException('At least width or height must be defined.');
        } elseif ($width === null) {
            $width = (int) round(($height / $this->getHeight()) * $this->getWidth());
        } elseif ($height === null) {
            $height = (int) round(($width / $this->getWidth()) * $this->getHeight());
        }

        /* @var $layer \Phower\Image\LayerInterface */
        foreach ($this->layers as $layer) {
            $posX = (int) round(($width / $layer->getWidth()) * $layer->getPosX());
            $posY = (int) round(($height / $layer->getHeight()) * $layer->getPosY());
            $layer->resize($width, $height, $posX, $posY);
        }

        $this->setWidth($width);
        $this->setHeight($height);

        return $this;
    }

    /**
     * Scale image through a given ratio
     * 
     * @param float $ratio
     * @return \Phower\Image\Image
     * @throws InvalidArgumentException
     */
    public function scale($ratio)
    {
        if (!is_numeric($ratio)) {
            throw new InvalidArgumentException('Ratio must be a numeric value.');
        }

        $width = round($this->width * $ratio);
        $height = round($this->height * $ratio);

        $this->resize($width, $height);

        return $this;
    }

}
