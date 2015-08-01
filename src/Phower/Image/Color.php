<?php

namespace Phower\Image;

use Phower\Image\Exception\InvalidArgumentException;

class Color implements ColorInterface
{

    protected $red;
    protected $green;
    protected $blue;
    protected $alpha;

    /**
     * Construct new instance
     * 
     * @param int|float $red
     * @param int|float $green
     * @param int|float $blue
     * @param int|float $alpha
     */
    public function __construct($red = 255, $green = 255, $blue = 255, $alpha = 0)
    {
        $this->setRed($red);
        $this->setGreen($green);
        $this->setBlue($blue);
        $this->setAlpha($alpha);
    }

    /**
     * Normalize hexadecimal color code
     * 
     * @param string $original
     * @return string
     * @throws InvalidArgumentException
     */
    public static function normalizeHex($original)
    {
        $normal = strtolower($original);

        if (substr($normal, 0, 1) === '#') {
            $normal = substr($normal, 1);
        }

        for ($i = 0; $i < strlen($normal); $i++) {
            if (false === strstr('01234567890abcdef', substr($normal, $i, 1))) {
                throw new InvalidArgumentException('Invalid hexadecimal value: ' . $original);
            }
        }

        if (strlen($normal) === 6) {
            $normal = '#' . $normal;
        } elseif (strlen($normal) === 3) {
            $original = '#';
            for ($i = 0; $i < 3; $i++) {
                $original .= str_repeat(substr($normal, $i, 1), 2);
            }
            $normal = $original;
        } else {
            throw new InvalidArgumentException('Invalid color value: ' . $original);
        }

        return $normal;
    }

    /**
     * Create a new instance of Color from an hexadecimal color code
     * 
     * @param string $color
     * @param float $alpha
     * @return \Phower\Image\Color
     */
    public static function fromHex($color, $alpha = 0)
    {
        $color = self::normalizeHex($color);
        $channels = [];

        for ($i = 0; $i < 3; $i++) {
            $hex = substr($color, $i * 2 + 1, 2);
            $channels[] = (int) hexdec($hex);
        }

        list($red, $green, $blue) = $channels;

        return new static($red, $green, $blue, $alpha);
    }

    /**
     * Create new instance of Color from an array of values
     * 
     * @param array $color
     * @param float $alpha
     * @return \Phower\Image\Color
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $color, $alpha = 0)
    {
        if (count($color) < 3 || count($color) > 4) {
            throw new InvalidArgumentException('Argument $color must be an array of 3 or 4 elements.');
        }
        
        if (count($color) === 3) {
            $color[] = 0;
        }

        list($red, $green, $blue, $alpha) = $color;

        return new static($red, $green, $blue, $alpha);
    }

    /**
     * Create a new instance of Color from a color name
     * 
     * @param string $name
     * @param float $alpha
     * @return \Phower\Image\Color
     * @throws InvalidArgumentException
     */
    public static function fromName($name, $alpha = 0)
    {
        $const = 'self::COLOR_' . strtoupper($name);

        if (!defined($const)) {
            throw new InvalidArgumentException('Undefined color name: ' . $name);
        }

        return self::fromHex(constant($const), $alpha);
    }

    /**
     * Create a new instance of Color from an integer
     * 
     * @param int $value
     * @return \Phower\Image\Color
     * @throws InvalidArgumentException
     */
    public static function fromInt($value)
    {
        if (!is_int($value) || $value < 0 || $value > 2147470591) {
            throw new InvalidArgumentException('Value must be an integer between 0 and 2147470591');
        }

        $alpha = $value >= 16777216 ? (int) floor($value / 16777216) : 0;
        $value = $value - $alpha * 16777216;

        $red = $value >= 65536 ? (int) floor($value / 65536) : 0;
        $value = $value - $red * 65536;

        $green = $value >= 256 ? (int) floor($value / 256) : 0;
        $value = $value - $green * 256;

        $blue = (int) $value;

        return new Color($red, $green, $blue, $alpha);
    }

    /**
     * Set channel
     * 
     * @param string $channel
     * @param int|float $value
     * @param int $max
     * @return \Phower\Image\Color
     * @throws InvalidArgumentException
     */
    protected function setChannel($channel, $value, $max = 255)
    {
        if (is_int($value) && $value >= 0 && $value <= $max) {
            $value = $value / $max;
        } elseif (is_float($value) && $value >= 0.0 && $value <= 1.0) {
            // do nothing
        } else {
            throw new InvalidArgumentException(sprintf('Value for channel channel must be an integer between 0 and %d or a float between 0.0 and 1.0.', $max));
        }

        $this->$channel = $value;

        return $this;
    }

    /**
     * Get channel
     * 
     * @param string $channel
     * @param int $name
     * @param int $decimals
     * @return mixed
     */
    protected function getChannel($channel, $mode, $max = 255, $decimals = 0)
    {
        switch ($mode) {
            case self::OUTPUT_INT :
                $value = (int) round($this->$channel * $max);
                break;
            case self::OUTPUT_FLOAT :
                $value = $this->$channel;
                break;
            case self::OUTPUT_HEX :
                $value = substr('00' . dechex($this->$channel * $max), -2);
                break;
            case self::OUTPUT_PERCENT :
                $value = number_format(($this->$channel) * 100, $decimals) . '%';
                break;
            default:
                throw new InvalidArgumentException('Invalid output mode.');
        }

        return $value;
    }

    /**
     * Set red
     * 
     * @param int|float $red
     * @return \Phower\Image\Color
     */
    public function setRed($red)
    {
        return $this->setChannel('red', $red);
    }

    /**
     * Get red
     * 
     * @param int $mode
     * @param int $decimals
     * @return mixed
     */
    public function getRed($mode = self::OUTPUT_INT, $decimals = 0)
    {
        return $this->getChannel('red', $mode, 255, $decimals);
    }

    /**
     * Set green
     * 
     * @param int|float $green
     * @return \Phower\Image\Color
     */
    public function setGreen($green)
    {
        return $this->setChannel('green', $green);
    }

    /**
     * Get green
     * 
     * @param int $mode
     * @param int $decimals
     * @return mixed
     */
    public function getGreen($mode = self::OUTPUT_INT, $decimals = 0)
    {
        return $this->getChannel('green', $mode, 255, $decimals);
    }

    /**
     * Set blue
     * 
     * @param int|float $blue
     * @return \Phower\Image\Color
     */
    public function setBlue($blue)
    {
        return $this->setChannel('blue', $blue);
    }

    /**
     * Get blue
     * 
     * @param int $mode
     * @param int $decimals
     * @return mixed
     */
    public function getBlue($mode = self::OUTPUT_INT, $decimals = 0)
    {
        return $this->getChannel('blue', $mode, 255, $decimals);
    }

    /**
     * Set alpha
     * 
     * @param int|float $alpha
     * @return \Phower\Image\Color
     */
    public function setAlpha($alpha)
    {
        return $this->setChannel('alpha', $alpha, 127);
    }

    /**
     * Get alpha
     * 
     * @param int $mode
     * @param int $decimals
     * @return mixed
     */
    public function getAlpha($mode = self::OUTPUT_INT, $decimals = 0)
    {
        return $this->getChannel('alpha', $mode, 127, $decimals);
    }

    /**
     * Returns true when this color is fully transparent (alpha equals 127)
     * 
     * @return boolean
     */
    public function isTransparent()
    {
        return $this->alpha === 1;
    }

    /**
     * Returns true when this color is opaque (alpha equals 0)
     * 
     * @return boolean
     */
    public function isOpaque()
    {
        return $this->alpha === 0;
    }

    /**
     * Return this color as an hexadecimal code
     * 
     * @return string
     */
    public function toHex()
    {
        $red = $this->getRed(self::OUTPUT_HEX);
        $green = $this->getGreen(self::OUTPUT_HEX);
        $blue = $this->getBlue(self::OUTPUT_HEX);

        $color = strtolower('#' . $red . $green . $blue);

        return $color;
    }

    /**
     * Output this Color value as an hexadecimal code
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->toHex();
    }

    /**
     * Return this color as an array
     * 
     * @return array
     */
    public function toArray($mode = self::OUTPUT_INT, $decimals = 0)
    {
        return [
            $this->getRed($mode, $decimals), 
            $this->getGreen($mode, $decimals), 
            $this->getBlue($mode, $decimals), 
            $this->getAlpha($mode, $decimals), 
            ];
    }

    /**
     * Return this color as an integer
     * 
     * @return int
     */
    public function toInt()
    {
        $color = $this->getRed() * 65536;
        $color += $this->getGreen() * 256;
        $color += $this->getBlue();
        $color += $this->getAlpha() * 16777216;

        return (int) $color;
    }

    /**
     * Return this color on a CSS standard RGB notation
     * 
     * @return string
     */
    public function toRgb()
    {
        $red = $this->getRed(self::OUTPUT_INT);
        $green = $this->getGreen(self::OUTPUT_INT);
        $blue = $this->getBlue(self::OUTPUT_INT);

        $color = sprintf('rgb(%d, %d, %d)', $red, $green, $blue);

        return $color;
    }

    /**
     * Return this color on a CSS standard RGBa notation
     * 
     * @return string
     */
    public function toRgba()
    {
        $red = $this->getRed(self::OUTPUT_INT);
        $green = $this->getGreen(self::OUTPUT_INT);
        $blue = $this->getBlue(self::OUTPUT_INT);
        $alpha = $this->getAlpha(self::OUTPUT_FLOAT);

        $color = sprintf('rgb(%d, %d, %d, %f)', $red, $green, $blue, $alpha);

        return $color;
    }

}
