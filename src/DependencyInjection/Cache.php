<?php

namespace Blog\DependencyInjection;

use Blog\Logger\FileLogger;
use Blog\Logger\LoggerInterface;
use Memcache;

class Cache
{
    /**
     * @var Memcache
     */
    private $cache;

    public function __construct(Memcache $cache, LoggerInterface $logger)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $key The key that will be associated with the item
     * @param mixed $var The variable to store. Strings and integers are stored as is, other types are stored serialized
     * @return bool
     */
    public function add(string $key, $var): bool
    {
        $logger = Container::getLogger();
        $logger->log($key);

        return $this->cache->add($key, $var);
    }

    /**
     * Retrieves an item from Memcached
     *
     * @param array|string $key
     * @return array|false|string
     */
    public function get($key)
    {
        return $this->cache->get($key);
    }

    /**
     * Delete key from Memcached
     *
     * @param string $key The key associated with the item to delete
     * @return bool TRUE on success or FALSE on failure
     */
    public function delete(string $key): bool
    {
        return $this->cache->delete($key);
    }

    /**
     * Connects to Memcached using hostname and port
     *
     * @param string $string the hostname
     * @param int $int the port
     * @return bool
     */
    public function connect(string $string, int $int): bool
    {
        return $this->cache->connect('127.0.0.1', 11211);
    }
}