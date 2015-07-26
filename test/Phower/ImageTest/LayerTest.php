<?php

namespace Phower\ImageTest;

use PHPUnit_Framework_TestCase;
use Phower\Image\Layer;

class LayerTest extends PHPUnit_Framework_TestCase
{

    protected $resource;

    protected function setUp()
    {
        parent::setUp();
    }

    public function testConstructMethodRequiresResourceToBeAGdResourceAnImagickOrAGmagickInstance()
    {
        $file = __DIR__ . '/../../data/lisbon1.jpg';

        $resource = imagecreatefromjpeg($file);
        $layer = new Layer($resource);
        $this->assertInstanceOf('Phower\Image\Layer', $layer);

        $resource = new \Imagick($file);
        $layer = new Layer($resource);
        $this->assertInstanceOf('Phower\Image\Layer', $layer);

        $resource = fopen($file, 'r');
        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $layer = new Layer($resource);
    }

    public function testConstructMethodAcceptsPosXPosYAndNameArguments()
    {
        $file = __DIR__ . '/../../data/lisbon1.jpg';

        $resource = imagecreatefromjpeg($file);
        $layer = new Layer($resource, -10, 10, 'Layer 1');
        $this->assertInstanceOf('Phower\Image\Layer', $layer);
    }

    public function testGetResourceMethodReturnsCurrentResource()
    {
        $file = __DIR__ . '/../../data/lisbon1.jpg';

        $resource = imagecreatefromjpeg($file);
        $layer = new Layer($resource, -10, 10, 'Layer 1');
        $this->assertEquals($resource, $layer->getResource());
    }

    public function testSetNameMethodSetsLayerNameAndGetNameMethodGetsLayerName()
    {
        $file = __DIR__ . '/../../data/lisbon1.jpg';

        $resource = imagecreatefromjpeg($file);
        $layer = new Layer($resource);
        $name = 'My Layer';
        $layer->setName($name);
        $this->assertEquals($name, $layer->getName());
    }

    public function testSetPosXMethodSetsLayerPosXAndGetPosXMethodGetsLayerPosX()
    {
        $file = __DIR__ . '/../../data/lisbon1.jpg';

        $resource = imagecreatefromjpeg($file);
        $layer = new Layer($resource);
        $posX = -10;
        $layer->setPosX($posX);
        $this->assertEquals($posX, $layer->getPosX());
    }

    public function testSetPosYMethodSetsLayerPosYAndGetPosYMethodGetsLayerPosY()
    {
        $file = __DIR__ . '/../../data/lisbon1.jpg';

        $resource = imagecreatefromjpeg($file);
        $layer = new Layer($resource);
        $posY = 10;
        $layer->setPosY($posY);
        $this->assertEquals($posY, $layer->getPosY());
    }

}
