<?php

namespace Phower\ImageTest\Adapter;

require_once __DIR__ . '/../../../functions.php';

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_Error_Warning;
use Imagick;
use Phower\Image\Adapter\ImagickAdapter;

class ImagickAdapterTest extends PHPUnit_Framework_TestCase
{

    protected $errors;
    protected $resource;
    public static $mockExtensionLoadedImagick;
    public static $mockVersionCompare;
    public static $mockFilePutContents;

    protected function setUp()
    {
        parent::setUp();

        PHPUnit_Framework_Error_Warning::$enabled = true;
        $this->errors = error_reporting();

        $this->resource = new Imagick(__DIR__ . '/../../../data/lisbon1.jpg');

        self::$mockExtensionLoadedImagick = null;
        self::$mockVersionCompare = null;
        self::$mockFilePutContents = null;
    }

    protected function tearDown()
    {
        parent::tearDown();

        error_reporting($this->errors);
        unset($this->resource);
    }

    public function testImagickAdapterClassImplementsAdapterInterface()
    {
        $adapter = new ImagickAdapter($this->resource);
        $this->assertInstanceOf('Phower\Image\Adapter\AdapterInterface', $adapter);
    }

    public function testConstructMethodsThrowsAnExceptionWhenImagickIsNotInstalled()
    {
        self::$mockExtensionLoadedImagick = false;
        $this->setExpectedException('Phower\Image\Exception\RuntimeException');
        $adapter = new ImagickAdapter($this->resource);
    }

    public function testConstructMethodsThrowsAnExceptionWhenImagickVersionIsLowerThanRequired()
    {
        self::$mockVersionCompare = false;
        $this->setExpectedException('Phower\Image\Exception\RuntimeException');
        $adapter = new ImagickAdapter($this->resource);
    }

    public function testConstructMethodThrowsAnExceptionWhenResourceIsNotAnImagickInstance()
    {
        $resource = fopen(__DIR__ . '/../../../data/lisbon1.jpg', 'rb');
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $adapter = new ImagickAdapter($resource);
    }

    public function testFromFileMethodCreatesANewAdapterFromAFile()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $adapter = ImagickAdapter::fromFile($file);
        $this->assertInstanceOf('Phower\Image\Adapter\ImagickAdapter', $adapter);
    }

    public function testFromFileMethodThrowsAnExceptionWhenCantReadFileContent()
    {
        $file = 'not_a_file';
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $adapter = ImagickAdapter::fromFile($file);
    }

    public function testFromStringMethodCreatesANewAdapterFromAString()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $content = file_get_contents($file);
        $adapter = ImagickAdapter::fromString($content);
        $this->assertInstanceOf('Phower\Image\Adapter\ImagickAdapter', $adapter);
    }

    public function testFromStringMethodThrowsAnExceptionWhenTempFileCantBeCreated()
    {
        self::$mockFilePutContents = false;
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $this->setExpectedException('Phower\Image\Exception\RuntimeException');
        $adapter = ImagickAdapter::fromString($file);
    }

    public function testCreateMethodCreatesNewAdapterWithAnEmptyImage()
    {
        $adapter = ImagickAdapter::create(200, 100);
        $this->assertInstanceOf('Phower\Image\Adapter\AdapterInterface', $adapter);
    }

    public function testCreateMethodThrowsAnExceptionWhenWidthIsLessThan1()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $adapter = ImagickAdapter::create(0, 100);
    }

    public function testCreateMethodThrowsAnExceptionWhenHeightIsLessThan1()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $adapter = ImagickAdapter::create(100, 0);
    }

    public function testGetResourceMethodReturnsCurrentResource()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $adapter = ImagickAdapter::fromFile($file);
        $resource = $adapter->getResource();
        $this->assertInstanceOf('Imagick', $resource);
    }

    public function testGetWidthMethodReturnsImageWidth()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $image = ImagickAdapter::fromFile($file);
        $this->assertEquals(1280, $image->getWidth());
    }

    public function testGetHeightMethodReturnsImageHeight()
    {
        $file = __DIR__ . '/../../../data/lisbon1.jpg';
        $image = ImagickAdapter::fromFile($file);
        $this->assertEquals(845, $image->getHeight());
    }
}
