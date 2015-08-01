<?php

namespace Phower\ImageTest;

use PHPUnit_Framework_TestCase;
use Phower\Image\Color;
use Phower\Image\ColorInterface;

class ColorTest extends PHPUnit_Framework_TestCase
{

    public function testColorClassImplementsColorInterface()
    {
        $color = new Color();
        $this->assertInstanceOf('Phower\Image\ColorInterface', $color);
    }

    public function testNormalizeHexReturnsNormalizedHexadecimalColorCode()
    {
        $color = Color::normalizeHex('FFFFFF');
        $this->assertEquals('#ffffff', $color);
        
        $color = Color::normalizeHex('#FFF');
        $this->assertEquals('#ffffff', $color);
    }

    public function testNormalizeHexThrowsExceptionWhenOriginalIsNotValidHexadecimal()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        Color::normalizeHex('XXX');
    }

    public function testNormalizeHexThrowsExceptionWhenOriginalIsNotValidColorCode()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        Color::normalizeHex('ffff');
    }
    
    public function testFromHexMethodCreatesNewInstanceFromHexadecimalCode()
    {
        $color = Color::fromHex('#ffcc00');
        $this->assertEquals(255, $color->getRed());
        $this->assertEquals(204, $color->getGreen());
        $this->assertEquals(0, $color->getBlue());
    }

    public function testFromArrayMethodCreatesNewInstanceFromAnArrayWithRGBValues()
    {
        $color = Color::fromArray([255, 204, 0]);
        $this->assertEquals(255, $color->getRed());
        $this->assertEquals(204, $color->getGreen());
        $this->assertEquals(0, $color->getBlue());
        $this->assertEquals(0, $color->getAlpha());
        
        $color = Color::fromArray([255, 204, 0, 64]);
        $this->assertEquals(255, $color->getRed());
        $this->assertEquals(204, $color->getGreen());
        $this->assertEquals(0, $color->getBlue());
        $this->assertEquals(64, $color->getAlpha());
    }

    public function testFromArrayMethodThrowsExceptionWhenArrayDoesntHave3Or4ElementsOnly()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $color = Color::fromArray([255, 204]);
    }

    public function testFromNameMethodCreatesNewInstanceFromAPredefinedName()
    {
        $color = Color::fromName('blue');
        $this->assertEquals(0, $color->getRed());
        $this->assertEquals(0, $color->getGreen());
        $this->assertEquals(255, $color->getBlue());
    }

    public function testFromNameMethodThrowsExceptionWhenNameIsUndefined()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $color = Color::fromName('speedorange');
    }

    public function testFromIntMethodCreatesNewInstanceFromAnIntgerValue()
    {
        $color = Color::fromInt(16763904);
        $this->assertEquals(255, $color->getRed());
        $this->assertEquals(204, $color->getGreen());
        $this->assertEquals(0, $color->getBlue());
    }

    public function testFromIntMethodThrowsExceptionWhenValueIsNotBetween0And2147470591()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $color = Color::fromInt(9999999999);
    }

    public function testSetRedMethodThrowsExceptioWhenRedValueIsIntegerAndIsNotBetween0And255()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $color = new Color(300);
    }

    public function testSetGreenMethodThrowsExceptioWhenGreenValueIsIntegerAndIsNotBetween0And255()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $color = new Color(0, 300);
    }

    public function testSetBlueMethodThrowsExceptioWhenBlueValueIsIntegerAndIsNotBetween0And255()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $color = new Color(0, 0, 300);
    }

    public function testSetAlphaMethodThrowsExceptioWhenAlphaValueIsIntegerAndIsNotBetween0And127()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $color = new Color(0, 0, 0, 128);
    }

    public function testGetRedChannelMethodReturnsValueForRedChannel()
    {
        $color = new Color(255, 204, 0, 0.5);
        $this->assertEquals(255, $color->getRed(ColorInterface::OUTPUT_INT));
        $this->assertEquals('ff', $color->getRed(ColorInterface::OUTPUT_HEX));
        $this->assertEquals(1.0, $color->getRed(ColorInterface::OUTPUT_FLOAT));
        $this->assertEquals('100.0%', $color->getRed(ColorInterface::OUTPUT_PERCENT, 1));
        
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $color->getRed(null);
    }

    public function testGetGreenChannelMethodReturnsValueForGreenChannel()
    {
        $color = new Color(255, 204, 0, 0.5);
        $this->assertEquals(204, $color->getGreen());
    }

    public function testGetBlueChannelMethodReturnsValueForBlueChannel()
    {
        $color = new Color(255, 204, 0, 0.5);
        $this->assertEquals(0, $color->getBlue());
    }

    public function testGetAlphaChannelMethodReturnsValueForAlphaChannel()
    {
        $color = new Color(255, 204, 0, 0.5);
        $this->assertEquals(64, $color->getAlpha());
    }

    public function testIsTransparentMethodReturnsTrueWhenColorIsFullyTransparent()
    {
        $color = new Color(255, 255, 255, 127);
        $this->assertTrue($color->isTransparent());
        
        $color = new Color(255, 255, 255, 0.5);
        $this->assertFalse($color->isTransparent());
    }

    public function testIsOpaqueMethodReturnsTrueWhenColorIsFullyOpaque()
    {
        $color = new Color(255, 255, 255, 0);
        $this->assertTrue($color->isOpaque());
        
        $color = new Color(255, 255, 255, 0.5);
        $this->assertFalse($color->isOpaque());
    }

    public function testToHexMethodReturnsColorAsAnHexadecimalCode()
    {
        $color = new Color(255, 204, 0);
        $this->assertEquals('#ffcc00', $color->toHex());
        
        $color = new Color(0, 0, 0);
        $this->assertEquals('#000000', $color->toHex());
    }

    public function testToStringMethodReturnsColorAsAnHexadecimalCode()
    {
        $color = new Color(255, 204, 0);
        $this->assertEquals('#ffcc00', sprintf($color));
    }

    public function testToArrayMethodReturnsColorAsAnArrayContainingRGBValues()
    {
        $color = new Color(255, 204, 0, 0.5);
        $this->assertEquals([255, 204, 0, 64], $color->toArray());
    }

    public function testToIntMethodReturnsColorAsAnIntegerValue()
    {
        $color = new Color(255, 204, 0, 0.5);
        $this->assertEquals(1090505728, $color->toInt());
    }

    public function testToRgbMethodReturnsColorAsAStandardCssRgbNotation()
    {
        $color = new Color(255, 204, 0);
        $this->assertEquals('rgb(255, 204, 0)', $color->toRgb());
    }

    public function testToRgbaMethodReturnsColorAsAStandardCssRgbaNotation()
    {
        $color = new Color(255, 204, 0, 64);
        $this->assertEquals('rgb(255, 204, 0, 0.503937)', $color->toRgba());
    }

}
