<?php

namespace Blog\Model\Entity;

class CacheEntity
{
    private $cache;

    /**
     * @return mixed
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param mixed $cache
     */
    public function setCache($cache): void
    {
        $this->cache = $cache;
    }


}