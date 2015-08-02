<?php

namespace Phower\ImageTest;

use PHPUnit_Framework_TestCase;
use Phower\Image\LayersStack;
use Phower\Image\Layer;

class LayersStackTest extends PHPUnit_Framework_TestCase
{

    public function testLayersStackImplementsCountable()
    {
        $stack = new LayersStack();
        $this->assertInstanceOf('Countable', $stack);
    }

    public function testCountMethosReturnsNumberOfLayersInTheStack()
    {
        $stack = new LayersStack();
        $stack->append($this->getMockBuilder('Phower\Image\LayerInterface')->getMock());
        $stack->append($this->getMockBuilder('Phower\Image\LayerInterface')->getMock());
        $stack->append($this->getMockBuilder('Phower\Image\LayerInterface')->getMock());
        $this->assertEquals(3, $stack->count());
    }

    public function testLayersStackImplementsArrayAccess()
    {
        $stack = new LayersStack();
        $this->assertInstanceOf('ArrayAccess', $stack);
    }

    public function testOffsetExistsMethodReturnsTrueWhenAnOffsetExists()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();
        $layers = new LayersStack();
        $this->assertFalse($layers->offsetExists(0));

        $layers->append($layer);
        $this->assertTrue($layers->offsetExists(0));
    }

    public function testOffsetGetMethodReturnsElementOfAGivenOffset()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();
        $layers = new LayersStack();
        $layers->append($layer);
        $this->assertEquals($layer, $layers->offsetGet(0));
        $this->assertNull($layers->offsetGet(1));
    }

    public function testOffsetSetMethodSetElementOfAGivenOffset()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();

        $layers = new LayersStack();
        $layers->append($layer);
        $layers->offsetSet(0, $layer);

        $this->assertEquals($layer, $layers->offsetGet(0));
    }

    public function testOffsetSetMethodThrowsExceptionWhenOffsetIsLowerThen0()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();

        $layers = new LayersStack();

        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $layers->offsetSet(-1, $layer);
    }

    public function testOffsetSetMethodThrowsExceptionWhenOffsetDoesNotExist()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();

        $layers = new LayersStack();

        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $layers->offsetSet(10, $layer);
    }

    public function testOffsetSetMethodThrowsExceptionWhenValueIsNotInstanceOfLayerInterface()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();

        $layers = new LayersStack();
        $layers->append($layer);

        $this->setExpectedException('Phower\Image\Exception\InvalidArgumentException');
        $layers->offsetSet(0, 'layer');
    }

    public function testOffsetUnetMethodRemovesAnExistingElement()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();

        $layers = new LayersStack();
        $layers->append($layer);
        $this->assertTrue($layers->offsetExists(0));

        $layers->offsetUnset(0);
        $this->assertFalse($layers->offsetExists(0));
    }

    public function testLayersStackImplementsIterator()
    {
        $stack = new LayersStack();
        $this->assertInstanceOf('Iterator', $stack);
    }

    public function testRewindMethodsMoveInternalPositionTo0()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();

        $layers = new LayersStack();
        $layers->append($layer);
        $layers->append($layer);
        $layers->append($layer);
        $layers->rewind();
        $this->assertEquals(0, $layers->key());
    }

    public function testCurrentMethodsReturnsElementForCurrentPosition()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();

        $layers = new LayersStack();
        $this->assertNull($layers->current());

        $layers->append($layer);
        $this->assertEquals($layer, $layers->current());
    }

    public function testKeyMethodsReturnsCurrentPosition()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();

        $layers = new LayersStack();
        $layers->append($layer);
        $this->assertEquals(0, $layers->key());
    }

    public function testNextMethodsIncrementsPositionBy1()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();

        $layers = new LayersStack();
        $this->assertEquals(0, $layers->key());
        $layers->next();
        $this->assertEquals(1, $layers->key());
    }

    public function testValidMethodsReturnsTrueWhenCurrentPositionExists()
    {
        $layer = $this->getMockBuilder('Phower\Image\LayerInterface')
                ->getMock();

        $layers = new LayersStack();
        $this->assertFalse($layers->valid());
        $layers->append($layer);
        $this->assertTrue($layers->valid());
    }

    public function testAppendMethodAddsElementToTheTopOrTheBottomOfTheStack()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        
        $layer1 = new Layer($adapter);
        $layer1->setName('Layer 1');

        $layer2 = new Layer($adapter);
        $layer2->setName('Layer 2');

        $layers = new LayersStack();

        $layers->append($layer1, LayersStack::APPEND_TOP);
        $this->assertEquals(1, $layers->count());

        $layers->append($layer2, LayersStack::APPEND_BOTTOM);
        $this->assertEquals(2, $layers->count());
        $this->assertEquals('Layer 2', $layers->offsetGet(1)->getName());
    }

    public function testMoveTopMethodMovesCurrentLayerToTheTop()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        
        $layer1 = new Layer($adapter);
        $layer1->setName('Layer 1');

        $layer2 = new Layer($adapter);
        $layer2->setName('Layer 2');

        $layer3 = new Layer($adapter);
        $layer3->setName('Layer 3');

        $layers = new LayersStack();
        $layers->append($layer1)
                ->append($layer2)
                ->append($layer3)
                ->next()
                ->next();
        $this->assertEquals(2, $layers->key());
        $this->assertEquals('Layer 1', $layers->current()->getName());

        $layers->moveTop();
        $this->assertEquals(0, $layers->key());
        $this->assertEquals('Layer 1', $layers->current()->getName());
    }

    public function testMoveBottomMethodMovesCurrentLayerToTheBottom()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        
        $layer1 = new Layer($adapter);
        $layer1->setName('Layer 1');

        $layer2 = new Layer($adapter);
        $layer2->setName('Layer 2');

        $layer3 = new Layer($adapter);
        $layer3->setName('Layer 3');

        $layers = new LayersStack();
        $layers->append($layer1)
                ->append($layer2)
                ->append($layer3);
        $this->assertEquals(0, $layers->key());
        $this->assertEquals('Layer 3', $layers->current()->getName());

        $layers->moveBottom();
        $this->assertEquals(2, $layers->key());
        $this->assertEquals('Layer 3', $layers->current()->getName());
    }

    public function testMoveUpMethodMovesCurrentLayerOnePositionUp()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        
        $layer1 = new Layer($adapter);
        $layer1->setName('Layer 1');

        $layer2 = new Layer($adapter);
        $layer2->setName('Layer 2');

        $layer3 = new Layer($adapter);
        $layer3->setName('Layer 3');

        $layers = new LayersStack();
        $layers->append($layer1)
                ->append($layer2)
                ->append($layer3);
        $this->assertEquals(0, $layers->key());
        $this->assertEquals('Layer 3', $layers->current()->getName());

        $layers->next()->next();
        $this->assertEquals(2, $layers->key());
        $this->assertEquals('Layer 1', $layers->current()->getName());
        
        $layers->moveUp();
        $this->assertEquals(1, $layers->key());
        $this->assertEquals('Layer 1', $layers->current()->getName());

        $layers->next();
        $this->assertEquals(2, $layers->key());
        $this->assertEquals('Layer 2', $layers->current()->getName());
    }

    public function testMoveDownMethodMovesCurrentLayerOnePositionDown()
    {
        $adapter = $this->getMockBuilder('Phower\Image\Adapter\AdapterInterface')
                ->getMock();
        
        $layer1 = new Layer($adapter);
        $layer1->setName('Layer 1');

        $layer2 = new Layer($adapter);
        $layer2->setName('Layer 2');

        $layer3 = new Layer($adapter);
        $layer3->setName('Layer 3');

        $layers = new LayersStack();
        $layers->append($layer1)
                ->append($layer2)
                ->append($layer3);
        $this->assertEquals(0, $layers->key());
        $this->assertEquals('Layer 3', $layers->current()->getName());

        $layers->moveDown();
        $this->assertEquals(1, $layers->key());
        $this->assertEquals('Layer 3', $layers->current()->getName());
    }

}
