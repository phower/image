<?php

namespace Phower\ImageTest;

use PHPUnit_Framework_TestCase;
use Phower\Image\Layer;

class LayerTest extends PHPUnit_Framework_TestCase
{

    public function testLayerClassImplementsLayerInterface()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $layer = new Layer($adapter);
        $this->assertInstanceOf('Phower\Image\LayerInterface', $layer);
    }

    public function testConstructMethodAcceptsNameArgument()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $layer = new Layer($adapter, 0, 0, 'Layer 1');
        $this->assertInstanceOf('Phower\Image\LayerInterface', $layer);
    }

    public function testGetAdapterReturnsCurrentAdapter()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $layer = new Layer($adapter, 0, 0, 'Layer 1');
        $this->assertEquals($adapter, $layer->getAdapter());
    }

    public function testSetNameMethodSetsNameAndGetNameMethodReturnsName()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $layer = new Layer($adapter);
        $name = 'Layer 1';
        $this->assertInstanceOf('Phower\Image\LayerInterface', $layer->setName($name));
        $this->assertEquals($name, $layer->getName());
    }

    public function testSetPosXMethodSetsPosXAndGetPosXMethodReturnsPosX()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $layer = new Layer($adapter);
        $posX = 10;
        $this->assertInstanceOf('Phower\Image\LayerInterface', $layer->setPosX($posX));
        $this->assertEquals($posX, $layer->getPosX());
    }

    public function testSetPosYMethodSetsPosYAndGetPosYMethodReturnsPosY()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $layer = new Layer($adapter);
        $posY = 10;
        $this->assertInstanceOf('Phower\Image\LayerInterface', $layer->setPosY($posY));
        $this->assertEquals($posY, $layer->getPosY());
    }

    public function testGetWidthMethodProxiesAdapterGetWidthMethod()
    {
        $width = 100;
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $adapter->method('getWidth')
                ->willReturn($width);
        $layer = new Layer($adapter);
        $this->assertEquals($width, $layer->getWidth());
    }

    public function testGetHeightMethodProxiesAdapterGetHeightMethod()
    {
        $height = 100;
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $adapter->method('getHeight')
                ->willReturn($height);
        $layer = new Layer($adapter);
        $this->assertEquals($height, $layer->getHeight());
    }

}
