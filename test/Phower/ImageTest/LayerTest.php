<?php

namespace Phower\ImageTest;

use PHPUnit_Framework_TestCase;
use Phower\Image\Layer;
use Phower\Image\LayerInterface;

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

    public function testAlignMethodAlignsLayerAcrossImageDimensions()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $adapter->method('getWidth')->willReturn(100);
        $adapter->method('getHeight')->willReturn(100);

        $layer = new Layer($adapter);

        $layer->align(200, 150, LayerInterface::POSITION_TOP_LEFT);
        $this->assertEquals(0, $layer->getPosX());
        $this->assertEquals(0, $layer->getPosY());

        $layer->align(200, 150, LayerInterface::POSITION_TOP_CENTER);
        $this->assertEquals(50, $layer->getPosX());
        $this->assertEquals(0, $layer->getPosY());

        $layer->align(200, 150, LayerInterface::POSITION_TOP_RIGHT);
        $this->assertEquals(100, $layer->getPosX());
        $this->assertEquals(0, $layer->getPosY());

        $layer->align(200, 150, LayerInterface::POSITION_MIDDLE_LEFT);
        $this->assertEquals(0, $layer->getPosX());
        $this->assertEquals(25, $layer->getPosY());

        $layer->align(200, 150, LayerInterface::POSITION_MIDDLE_CENTER);
        $this->assertEquals(50, $layer->getPosX());
        $this->assertEquals(25, $layer->getPosY());

        $layer->align(200, 150, LayerInterface::POSITION_MIDDLE_RIGHT);
        $this->assertEquals(100, $layer->getPosX());
        $this->assertEquals(25, $layer->getPosY());

        $layer->align(200, 150, LayerInterface::POSITION_BOTTOM_LEFT);
        $this->assertEquals(0, $layer->getPosX());
        $this->assertEquals(50, $layer->getPosY());

        $layer->align(200, 150, LayerInterface::POSITION_BOTTOM_CENTER);
        $this->assertEquals(50, $layer->getPosX());
        $this->assertEquals(50, $layer->getPosY());

        $layer->align(200, 150, LayerInterface::POSITION_BOTTOM_RIGHT);
        $this->assertEquals(100, $layer->getPosX());
        $this->assertEquals(50, $layer->getPosY());
    }

    public function testAlignMethodThrowsExceptionWhenImageWidthIsInvalid()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $adapter->method('getWidth')->willReturn(100);
        $adapter->method('getHeight')->willReturn(100);

        $layer = new Layer($adapter);

        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $layer->align(0, 10);
    }

    public function testAlignMethodThrowsExceptionWhenImageHeightIsInvalid()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $adapter->method('getWidth')->willReturn(100);
        $adapter->method('getHeight')->willReturn(100);

        $layer = new Layer($adapter);

        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $layer->align(10, 0);
    }

    public function testAlignMethodThrowsExceptionWhenPositionIsInvalid()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        $adapter->method('getWidth')->willReturn(100);
        $adapter->method('getHeight')->willReturn(100);

        $layer = new Layer($adapter);

        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $layer->align(10, 10, 10);
    }

    public function testResizeMethodScalesLayerAndAdjustItsPosition()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();

        $adapter->method('resize')->willReturn(true);
        $adapter->method('getWidth')->willReturn(100);
        $adapter->method('getHeight')->willReturn(100);

        $layer = new Layer($adapter);
        $layer->resize(100, 100, 10, 15);

        $this->assertEquals(100, $layer->getWidth());
        $this->assertEquals(100, $layer->getHeight());
        $this->assertEquals(10, $layer->getPosX());
        $this->assertEquals(15, $layer->getPosY());
    }

    public function testResizeMethodKeepsCurrentPositionWhenCorrdinatesAreNotSupplied()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();

        $adapter->method('resize')->willReturn(true);

        $layer = new Layer($adapter);
        $posX = $layer->getPosX();
        $posY = $layer->getPosY();
        
        $layer->resize(100, 100);

        $this->assertEquals($posX, $layer->getPosX());
        $this->assertEquals($posY, $layer->getPosY());
    }
}
