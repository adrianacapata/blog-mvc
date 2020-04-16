<?php

namespace Blog\DependencyInjection;

use PDO;
use Blog\Router\Request;

class Container
{
    /**
     * @var array
     */
    private static $parameters;
    /** @var PDO */
    private static $dbConnection;
    /** @var Request */
    private static $request;

    /**
     * @param string|null $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public static function getParameters(string $name = null)
    {
        if (self::$parameters === null) {
            require_once __DIR__ . '/../../config/parameters.php';
            self::$parameters = $parameters;
        }
        
        if (!isset(self::$parameters[$name])) {
            throw new \InvalidArgumentException("`$name` parameter is not defined");
        }

        return $name ? self::$parameters[$name] : self::$parameters;
    }

    /**
     * @return \PDO
     */
    public static function getDbConnection(): \PDO
    {
        if (self::$dbConnection === null) {
            $parameters = self::getParameters('db');

            self::$dbConnection = new \PDO(
                $parameters['hostname'],
                $parameters['username'],
                $parameters['password']
            );
        }

        self::$dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return self::$dbConnection;
    }

    public static function getRequest(): Request
    {
          return self::$request = Request::getInstance();
    }
}