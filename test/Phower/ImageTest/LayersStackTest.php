<?php

namespace Phower\ImageTest;

use PHPUnit_Framework_TestCase;
use Phower\Image\LayersStack;

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

    public function testLayersStackImplementsIterator()
    {
        $stack = new LayersStack();
        $this->assertInstanceOf('Iterator', $stack);
    }

}
