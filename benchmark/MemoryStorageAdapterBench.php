<?php

namespace LaminasBench\Cache;

use Laminas\Cache\StorageFactory;

/**
 * @Revs(100)
 * @Iterations(10)
 * @Warmup(1)
 */
class MemoryStorageAdapterBench extends CommonStorageAdapterBench
{
    public function __construct()
    {
        // instantiate the storage adapter
        $this->storage = StorageFactory::adapterFactory('memory');

        parent::__construct();
    }
}
