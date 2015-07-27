<?php

namespace Phower\Image;

use ArrayAccess;
use Countable;
use Iterator;
use Phower\Image\Exception\InvalidArgumentException;

class LayersStack implements Countable, ArrayAccess, Iterator
{

    const APPEND_TOP = 0;
    const APPEND_BOTTOM = 1;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var array
     */
    protected $layers = [];

    /**
     * Count existing layers
     * 
     * @param string $mode
     * @return int
     */
    public function count($mode = 'COUNT_NORMAL')
    {
        return count($this->layers);
    }

    /**
     * Whether a offset exists
     * 
     * @param int $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->layers[$offset]);
    }

    /**
     * Offset get
     * 
     * @param int $offset
     * @return LayerInterface|null
     */
    public function offsetGet($offset)
    {
        return isset($this->layers[$offset]) ? $this->layers[$offset] : null;
    }

    /**
     * Offset set
     * 
     * @param int $offset
     * @param LayerInterface $value
     * @return \Phower\Image\LayersStack
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if ((int) $offset < 0) {
            throw new InvalidArgumentException('Offset must be an integer greater or equal 0.');
        }

        if (!$value instanceof LayerInterface) {
            throw new InvalidArgumentException('Value must be an instance of \Phower\Image\LayerInterface.');
        }

        $this->layers[$offset] = $value;

        return $this;
    }

    /**
     * Unset offset
     * 
     * @param int $offset
     * @return \Phower\Image\LayersStack
     */
    public function offsetUnset($offset)
    {
        array_splice($this->layers, $offset, 1);

        if (!$this->valid()) {
            $this->rewind();
        }

        return $this;
    }

    /**
     * Rewind to the topmost layer
     * 
     * @return \Phower\Image\LayersStack
     */
    public function rewind()
    {
        $this->position = 0;
        return $this;
    }

    /**
     * Get current layer
     * 
     * @return LayerInterface
     */
    public function current()
    {
        return $this->layers[$this->position];
    }

    /**
     * Get position of current layer
     * 
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Move position to the next layer
     * 
     * @return \Phower\Image\LayersStack
     */
    public function next()
    {
        ++$this->position;
        return $this;
    }

    /**
     * Check if the current position is valid
     * 
     * @return boolean
     */
    public function valid()
    {
        return isset($this->layers[$this->position]);
    }

    /**
     * Append a new layer to the layers stack the change
     * position to the new layer
     * 
     * @param \Phower\Image\LayerInterface $layer
     * @param int $mode
     * @return \Phower\Image\LayersStack
     */
    public function append(LayerInterface $layer, $mode = self::APPEND_TOP)
    {
        if ($mode === self::APPEND_BOTTOM) {
            array_push($this->layers, $layer);
            $this->position = $this->count() - 1;
        } else {
            array_unshift($this->layers, $layer);
            $this->position = 0;
        }

        return $this;
    }

    /**
     * Move current layer to the top
     * 
     * @return \Phower\Image\LayersStack
     */
    public function moveTop()
    {
        if ($this->valid() && $this->key() > 0) {
            $layer = $this->current();
            $this->offsetUnset($this->key());
            $this->add($layer);
        }

        return $this;
    }

    /**
     * Move current layer to the bottom
     * 
     * @return \Phower\Image\LayersStack
     */
    public function moveBottom()
    {
        if ($this->valid() && $this->key() < $this->count() - 1) {
            $layer = $this->current();
            $this->offsetUnset($this->key());
            array_push($this->layers, $layer);
        }

        return $this;
    }

    /**
     * Move current layer one position up
     * 
     * @return \Phower\Image\LayersStack
     */
    public function moveUp()
    {
        if ($this->valid() && $this->key() > 0) {
            $key = $this->key();
            $layer = $this->current();
            $this->offsetSet($key, $this->offsetGet($key - 1));
            $this->offsetSet($key - 1, $layer);
        }

        return $this;
    }

    /**
     * Move current layer one position down
     * 
     * @return \Phower\Image\LayersStack
     */
    public function moveDown()
    {
        if ($this->valid() && $this->count() - 1) {
            $key = $this->key();
            $layer = $this->current();
            $this->offsetSet($key, $this->offsetGet($key + 1));
            $this->offsetSet($key + 1, $layer);
        }

        return $this;
    }

}
