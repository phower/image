<?php

namespace Phower\ImageTest\Adapter;

require_once __DIR__ . '/../../../functions.php';

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_Error_Warning;
use Phower\Image\Adapter\GdAdapter;

class GdAdapterTest extends PHPUnit_Framework_TestCase
{

    protected $errors;
    protected $resource;
    public static $mockFunctionExistsGdInfo;
    public static $mockGdInfoResult;
    public static $mockImagecopyresampled;

    protected function setUp()
    {
        parent::setUp();

        PHPUnit_Framework_Error_Warning::$enabled = true;
        $this->errors = error_reporting();

        $this->resource = imagecreatefromjpeg(__DIR__ . '/../../../data/lisbon1.jpg');

        self::$mockFunctionExistsGdInfo = null;
        self::$mockGdInfoResult = null;
        self::$mockImagecopyresampled = null;
    }

    protected function tearDown()
    {
        parent::tearDown();

        error_reporting($this->errors);

        if (is_resource($this->resource)) {
            unset($this->resource);
        }
    }

    public function testGdAdapterClassImplementsAdapterInterface()
    {
        $adapter = new GdAdapter($this->resource);
        $this->assertInstanceOf('Phower\Image\Adapter\AdapterInterface', $adapter);
    }

    public function testConstructMethodsThrowsAnExceptionWhenGdIsNotInstalled()
    {
        self::$mockFunctionExistsGdInfo = false;
        $this->setExpectedException('Phower\Image\Exception\RuntimeException');
        $adapter = new GdAdapter($this->resource);
    }

    public function testConstructMethodsThrowsAnExceptionWhenGdVersionIsLowerThanRequired()
    {
        self::$mockGdInfoResult = [
            'GD Version' => '2.0,'
        ];
        $this->setExpectedException('Phower\Image\Exception\RuntimeException');
        $adapter = new GdAdapter($this->resource);
    }

    public function testConstructMethodsThrowsAnExceptionWhenResourceIsNotAGdResource()
    {
        $resource = fopen(__DIR__ . '/../../../data/lisbon1.jpg', 'rb');
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $adapter = new GdAdapter($resource);
    }

    public function testConstructMethodAlwaysConvertResourceTrueColor()
    {
        $resource = imagecreatefromgif(__DIR__ . '/../../../data/lisbon1.gif');
        $this->assertFalse(imageistruecolor($resource));
        $adapter = new GdAdapter($resource);
        $this->assertTrue(imageistruecolor($adapter->getResource()));
    }

    public function testFromFileMethodCreatesANewAdapterFromAFile()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $adapter = GdAdapter::fromFile($file);
        $this->assertInstanceOf('Phower\Image\Adapter\GdAdapter', $adapter);
    }

    public function testFromFileMethodThrowsAnExceptionWhenCantReadFileContent()
    {
        PHPUnit_Framework_Error_Warning::$enabled = false;
        error_reporting(E_ERROR);

        $file = 'not_a_file';
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $adapter = GdAdapter::fromFile($file);
    }

    public function testFromStringMethodCreatesANewAdapterFromAString()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $content = file_get_contents($file);
        $adapter = GdAdapter::fromString($content);
        $this->assertInstanceOf('Phower\Image\Adapter\GdAdapter', $adapter);
    }

    public function testFromStringMethodThrowsAnExceptionWhenStringIsNotAnImage()
    {
        PHPUnit_Framework_Error_Warning::$enabled = false;
        error_reporting(E_ERROR);

        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $adapter = GdAdapter::fromString('not an image');
    }

    public function testCreateMethodCreatesNewAdapterWithAnEmptyImage()
    {
        $adapter = GdAdapter::create(200, 100);
        $this->assertInstanceOf('Phower\Image\Adapter\AdapterInterface', $adapter);
    }

    public function testCreateMethodThrowsAnExceptionWhenWidthIsLessThan1()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $adapter = GdAdapter::create(0, 100);
    }

    public function testCreateMethodThrowsAnExceptionWhenHeightIsLessThan1()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $image = GdAdapter::create(100, 0);
    }

    public function testGetResourceMethodReturnsCurrentResource()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $image = GdAdapter::fromFile($file);
        $resource = $image->getResource();
        $this->assertTrue(is_resource($resource));
        $this->assertEquals('gd', get_resource_type($resource));
    }

    public function testGetWidthMethodReturnsImageWidth()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $image = GdAdapter::fromFile($file);
        $this->assertEquals(1280, $image->getWidth());
    }

    public function testGetHeightMethodReturnsImageHeight()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $image = GdAdapter::fromFile($file);
        $this->assertEquals(845, $image->getHeight());
    }

    public function testResizeMethodScalesImageToNewDimensions()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $resource = imagecreatefromjpeg($file);
        $adapter = new GdAdapter($resource);
        $this->assertEquals(imagesx($resource), $adapter->getWidth());
        $this->assertEquals(imagesy($resource), $adapter->getHeight());
        
        $adapter->resize(120, 100);
        $this->assertEquals(120, $adapter->getWidth());
        $this->assertEquals(100, $adapter->getHeight());
    }
    
    public function testResizeMethodThrowsExceptionWhenWidthIsLessThen1()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $resource = imagecreatefromjpeg($file);
        $adapter = new GdAdapter($resource);

        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $adapter->resize(0, 100);
    }
    
    public function testResizeMethodThrowsExceptionWhenHeightIsLessThen1()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $resource = imagecreatefromjpeg($file);
        $adapter = new GdAdapter($resource);

        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $adapter->resize(100, 0);
    }
    
    public function testResizeMethodThrowsExceptionWhenImageCantBeResampled()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $resource = imagecreatefromjpeg($file);
        $adapter = new GdAdapter($resource);

        self::$mockImagecopyresampled = false;
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $adapter->resize(100, 100);
    }
}
