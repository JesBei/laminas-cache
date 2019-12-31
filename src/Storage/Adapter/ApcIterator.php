<?php

/**
 * @see       https://github.com/laminas/laminas-cache for the canonical source repository
 * @copyright https://github.com/laminas/laminas-cache/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-cache/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Cache\Storage\Adapter;

use APCIterator as BaseApcIterator;
use Laminas\Cache\Storage\IteratorInterface;

/**
 * @category   Laminas
 * @package    Laminas_Cache
 * @subpackage Storage
 */
class ApcIterator implements IteratorInterface
{

    /**
     * The apc storage instance
     *
     * @var Apc
     */
    protected $storage;

    /**
     * The iterator mode
     *
     * @var int
     */
    protected $mode = IteratorInterface::CURRENT_AS_KEY;

    /**
     * The base APCIterator instance
     *
     * @var BaseApcIterator
     */
    protected $baseIterator;

    /**
     * The length of the namespace prefix
     *
     * @var int
     */
    protected $prefixLength;

    /**
     * Constructor
     *
     * @param Apc             $storage
     * @param BaseApcIterator $baseIterator
     * @param string          $prefix
     */
    public function __construct(Apc $storage, BaseApcIterator $baseIterator, $prefix)
    {
        $this->storage      = $storage;
        $this->baseIterator = $baseIterator;
        $this->prefixLength = strlen($prefix);
    }

    /**
     * Get storage instance
     *
     * @return Apc
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Get iterator mode
     *
     * @return int Value of IteratorInterface::CURRENT_AS_*
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set iterator mode
     *
     * @param int $mode
     * @return ApcIterator Fluent interface
     */
    public function setMode($mode)
    {
        $this->mode = (int) $mode;
        return $this;
    }

    /* Iterator */

    /**
     * Get current key, value or metadata.
     *
     * @return mixed
     */
    public function current()
    {
        if ($this->mode == IteratorInterface::CURRENT_AS_SELF) {
            return $this;
        }

        $key = $this->key();

        if ($this->mode == IteratorInterface::CURRENT_AS_VALUE) {
            return $this->storage->getItem($key);
        } elseif ($this->mode == IteratorInterface::CURRENT_AS_METADATA) {
            return $this->storage->getMetadata($key);
        }

        return $key;
    }

    /**
     * Get current key
     *
     * @return string
     */
    public function key()
    {
        $key = $this->baseIterator->key();

        // remove namespace prefix
        return substr($key, $this->prefixLength);
    }

    /**
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
        $this->baseIterator->next();
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->baseIterator->valid();
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @return void
     */
    public function rewind()
    {
        return $this->baseIterator->rewind();
    }
}
