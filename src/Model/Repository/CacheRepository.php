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

    public function cachedQuery($key, $query)
    {
        $check = $this->cache->get($key);

        if (!$check) {
            $this->cache->add($key, $query);
        }

        return $check;
    }
}