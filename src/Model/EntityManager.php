<?php

namespace Blog\Model;

use Blog\DependencyInjection\Cache;

class EntityManager
{
    private $cache;
    private $connection;

    public function __construct(Cache $cache, $connection)
    {
        $this->cache = $cache;
        $this->connection = $connection;
    }

    public function addOrReturnFromCache($key, $value)
    {
        $check = $this->cache->get($key);

        if (!$check) {
            $this->cache->add($key, $value);
        }

        return $check;
    }
}