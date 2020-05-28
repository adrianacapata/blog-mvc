<?php

namespace Blog\DependencyInjection;

use Blog\Logger\DbLogger;
use Blog\Logger\FileLogger;
use Blog\Logger\Logger;
use Blog\Logger\LoggerInterface;
use Blog\Model\EntityManager;
use InvalidArgumentException;
use Memcache;
use PDO;
use Blog\Router\Request;
use Swift_SmtpTransport;

class Container
{
    /** @var array */
    private static $parameters;
    /** @var PDO */
    private static $dbConnection;
    /** @var Request */
    private static $request;
    /** @var Swift_SmtpTransport */
    private static $mailer;
    /** @var Memcache */
    private static $cache;
    /** @var LoggerInterface */
    private static $logger;

    /**
     * @param string|null $name
     * @return mixed
     * @throws InvalidArgumentException
     */
    public static function getParameters(string $name = null)
    {
        if (self::$parameters === null) {
            require_once __DIR__ . '/../../config/parameters.php';
            self::$parameters = $parameters;
        }
        
        if (!isset(self::$parameters[$name])) {
            throw new InvalidArgumentException("`$name` parameter is not defined");
        }

        return $name ? self::$parameters[$name] : self::$parameters;
    }

    /**
     * @return PDO
     */
    public static function getDbConnection(): PDO
    {
        if (self::$dbConnection === null) {
            $parameters = self::getParameters('db');

            self::$dbConnection = new PDO(
                $parameters['hostname'],
                $parameters['username'],
                $parameters['password']
            );
        }

        self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return self::$dbConnection;
    }

    public static function getRequest(): Request
    {
          return self::$request = Request::getInstance();
    }

    public static function getMailer(): Mailer
    {
        if (self::$mailer === null) {
            $parameters = self::getParameters('swift_mailer');

            $smtpTransport = (new Swift_SmtpTransport($parameters['host'], $parameters['port'], $parameters['encryption']))
                ->setUsername($parameters['sender_address'])
                ->setPassword($parameters['password']);

            self::$mailer = new Mailer(new \Swift_Mailer($smtpTransport));
        }

        return self::$mailer;
    }

    //getLogger - get from params type (file or db) - return loggerInterface
    public static function getLogger(): LoggerInterface
    {
        if (self::$logger === null) {
            $parameters = self::getParameters('logger');
            self::$logger = new Logger(new FileLogger($parameters['path']));
        }

        return self::$logger;
    }

    public static function getCache()
    {
        if (self::$cache === null) {
            $parameters = self::getParameters('memcached');

            self::$cache = (new Cache(new Memcache(), self::getLogger()));
            self::$cache->connect($parameters['host'], $parameters['port']);
        }

        return self::$cache;
    }

    public static function getRepository($repository)
    {
        $repositories[$repository] = new $repository();

        return $repositories[$repository];
    }
    //TODO get repository - array with class names as keys
    //a nea cachedRepo class -
}