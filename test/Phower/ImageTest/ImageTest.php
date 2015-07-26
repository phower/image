<?php

namespace Phower\ImageTest;

use PHPUnit_Framework_TestCase;
use Phower\Image\Image;
use Phower\Image\Adapter\AdapterInterface;

class ImageTest extends PHPUnit_Framework_TestCase
{

    protected $adapter;
    protected $image;

    protected function setUp()
    {
        parent::setUp();
        $this->adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $this->image = new Image($this->adapter, 200, 200);
    }

    public function testConstructMethodRequiresAdapterInterfaceWidthAndHeight()
    {
        $this->assertInstanceOf('Phower\Image\Image', $this->image);
    }

    public function testConstructMethodRequiresWidthToBeGreaterThanZero()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $this->image = new Image($this->adapter, 0, 200);
    }

    public function testConstructMethodRequiresHeightToBeGreaterThanZero()
    {
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $this->image = new Image($this->adapter, 200, 0);
    }

    public function testGetAdapterMethodReturnsAdapter()
    {
        $this->assertInstanceOf('Phower\Image\Adapter\AdapterInterface', $this->image->getAdapter());
    }

    public function testGetWidthMethodReturnsWidth()
    {
        $this->assertEquals(200, $this->image->getWidth());
    }

    public function testGetHeightMethodReturnsHeight()
    {
        $this->assertEquals(200, $this->image->getHeight());
    }

    public function testGetLayersMethodReturnsInstanceOfLayersStack()
    {
        $this->assertInstanceOf('Phower\Image\LayersStack', $this->image->getLayers());
    }

}
