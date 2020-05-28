<?php

namespace Blog\Model\Repository;

use Blog\DependencyInjection\Cache;

class CacheRepository
{
    /**
     * @var Cache
     */
    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function cachedQuery($key, $callback)
    {
        $check = $this->cache->get($key);

        if (!$check) {
            $this->cache->add($key, $callback);
            $check = $callback;
        }

        return $check;
    }
}