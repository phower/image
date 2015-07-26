<?php

namespace Phower\Image\Adapter;

use Phower\Image\Image;
use Phower\Image\Layer;
use Phower\Image\Exception\InvalidArgumentException;

class GdAdapter implements AdapterInterface
{

    /**
     * Create Image instance from file
     * 
     * @param string $file
     * @return Image
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
     * Create Image instance from string
     * 
     * @param string $string
     * @return Image
     * @throws InvalidArgumentException
     */
    public static function fromString($string)
    {
        if (!$resource = imagecreatefromstring($string)) {
            throw new InvalidArgumentException('Unable to create an image from the given string.');
        }

        $adapter = new static();
        $width = imagesx($resource);
        $height = imagesy($resource);

        $layer = new Layer($resource);

        $image = new Image($adapter, $width, $height);
        $image->getLayers()->append($layer);

        return $image;
    }

}
