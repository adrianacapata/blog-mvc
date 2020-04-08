<?php

namespace Blog\Router;

class RouterConfig
{
    /**
     * @var array
     */
    private static $routes;

    public function __construct(array $routes)
    {
        self::$routes = $routes;
    }

    public static function getRoutes()
    {
        return self::$routes;
    }
}