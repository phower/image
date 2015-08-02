<?php

/**
 * Native functions mocked for Phower\Image namespace
 */

namespace Phower\Image;

use Phower\ImageTest\ImageTest;

function class_exists($name)
{
    if ($name === 'Imagick' && null !== ImageTest::$mockClassExistsImagick) {
        return ImageTest::$mockClassExistsImagick;
    }
    return \class_exists($name);
}

function function_exists($name)
{
    if ($name === 'gd_info' && null !== ImageTest::$mockFunctionExistsGdInfo) {
        return ImageTest::$mockFunctionExistsGdInfo;
    }
    return \function_exists($name);
}

/**
 * Phower\Image test case
 */

namespace Phower\ImageTest;

use PHPUnit_Framework_TestCase;
use Phower\Image\Image;
use Phower\Image\ImageInterface;
use Phower\Image\LayersStack;

class ImageTest extends PHPUnit_Framework_TestCase
{

    public static $mockClassExistsImagick;
    public static $mockFunctionExistsGdInfo;

    protected function setUp()
    {
        parent::setUp();

        self::$mockClassExistsImagick = null;
        self::$mockFunctionExistsGdInfo = null;
    }

    public function testImageClassImplementsImageInterface()
    {
        $image = new Image();
        $this->assertInstanceOf('Phower\Image\ImageInterface', $image);
    }

    public function testSetDefaultAdapterMethodAcceptsArgumentToBeAnInstanceOfAdapterInterface()
    {
        $defaultAdapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $mockClassName = get_class($defaultAdapter);
        $image = new Image($defaultAdapter);
        $this->assertEquals($mockClassName, $image->getDefaultAdapter());
    }

    /**
     * @dataProvider adapterProvider
     */
    public function testSetDefaultAdapterMethodAcceptsArgumentToBeTheClassNameOfAnInstanceOfAdapterInterface($adapter)
    {
        $image = new Image($adapter);
        $this->assertEquals($adapter, $image->getDefaultAdapter());
    }

    public function adapterProvider()
    {
        return [
            ['Phower\Image\Adapter\GdAdapter'],
            ['Phower\Image\Adapter\ImagickAdapter'],
        ];
    }

    /**
     * @dataProvider aliasProvider
     */
    public function testSetDefaultAdapterMethodAcceptsArgumentToBeAnAliasForAnInstanceOfAdapterInterface($alias, $className)
    {
        $image = new Image($alias);
        $this->assertEquals($className, $image->getDefaultAdapter());
    }

    public function aliasProvider()
    {
        return [
            [ImageInterface::ADAPTER_GD, 'Phower\Image\Adapter\GdAdapter'],
            [ImageInterface::ADAPTER_IMAGICK, 'Phower\Image\Adapter\ImagickAdapter'],
        ];
    }

    public function testSetDefaultAdapterMethodsThrowsExceptionOnInvalidArgument()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $image = new Image('NotAnAdapter');
    }

    public function testGetDefaultAdapterMethodDefaultsToImagick()
    {
        $image = new Image();
        self::$mockClassExistsImagick = true;
        $this->assertEquals('Phower\Image\Adapter\ImagickAdapter', $image->getDefaultAdapter());
    }

    public function testGetDefaultAdapterMethodFallbacksToGdWhenImagickIsNotAvailable()
    {
        $image = new Image();
        self::$mockClassExistsImagick = false;
        self::$mockFunctionExistsGdInfo = true;
        $this->assertEquals('Phower\Image\Adapter\GdAdapter', $image->getDefaultAdapter());
    }

    public function testGetDefaultAdapterMethodThrowsAnExceptionWhenNoneOfTheLibrariesIsAvailable()
    {
        $image = new Image();
        self::$mockClassExistsImagick = false;
        self::$mockFunctionExistsGdInfo = false;
        $this->setExpectedException('Phower\Image\Exception\RuntimeException');
        $image->getDefaultAdapter();
    }

    /**
     * 
     * @dataProvider widthProvider
     */
    public function testSetWidthMethodRequiresArgumentToBeIntGreaterThanZeroEquivalent($width)
    {
        $image = new Image(null, $width);
        $this->assertEquals((int) $width, $image->getWidth());
    }

    public function widthProvider()
    {
        return [
            [100],
            ['100'],
            ['100abc'],
        ];
    }

    public function testSetWidthMethodThrowsAnExceptionWhenArgumentIsNotIntGreaterThanZeroEquivalent()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $image = new Image(null, 0);
    }

    /**
     * 
     * @dataProvider heightProvider
     */
    public function testSetHeightMethodRequiresArgumentToBeIntGreaterThanZeroEquivalent($height)
    {
        $image = new Image(null, null, $height);
        $this->assertEquals((int) $height, $image->getHeight());
    }

    public function heightProvider()
    {
        return [
            [100],
            ['100'],
            ['100abc'],
        ];
    }

    public function testSetHeightMethodThrowsAnExceptionWhenArgumentIsNotIntGreaterThanZeroEquivalent()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $image = new Image(null, null, 0);
    }

    public function testConstructMethodInjectsAnEmptyInstanceOfLayersStack()
    {
        $image = new Image();
        $this->assertEquals(new LayersStack(), $image->getLayers());
    }

    public function testImportMethodImportsImageFileOrUrlIntoNewLayer()
    {
        $file = __DIR__ . '/../../data/lisbon1.jpg';
        $image = new Image('gd', 200, 150);
        $this->assertEquals(0, $image->getLayers()->count());

        $image->import($file);
        $this->assertEquals(1, $image->getLayers()->count());
    }

    public function testImportMethodThrowsExceptionWhenIsUnableToReadSource()
    {
        $image = new Image('gd', 200, 150);
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $image->import('not_a_file');
    }

    public function testImportMethodAdjustsImageWidthAndHeightOnFirstImportWhenThoseAreNotDefined()
    {
        $file = __DIR__ . '/../../data/lisbon1.jpg';
        $image = new Image('gd');
        $this->assertNull($image->getWidth());
        $this->assertNull($image->getHeight());

        $image->import($file);
        $this->assertEquals($image->getLayers()->current()->getWidth(), $image->getWidth());
        $this->assertEquals($image->getLayers()->current()->getHeight(), $image->getHeight());
    }

}
