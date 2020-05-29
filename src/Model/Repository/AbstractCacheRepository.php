<?php

namespace Blog\Model\Repository;

use Blog\DependencyInjection\Cache;

abstract class AbstractCacheRepository
{
    private Cache $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return mixed
     */
    protected function cachedQuery(string $key, callable $callback)
    {
        $value = $this->cache->get($key);

        if (!$value) {
            $value = $callback();
            $this->cache->add($key, $value);
        }

        return $value;
    }
}