<?php

namespace Blog\DependencyInjection;

class Container implements ContainerInterface
{
    private $dbConnection;
    /**
     * @var array
     */
    private $parameters;

    /**
     * Container constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param string|null $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getParameters(string $name = null)
    {
        if (!isset($this->parameters[$name])) {
            throw new \InvalidArgumentException("`$name` parameter is not defined");
        }

        return $name ? $this->parameters[$name] : $this->parameters;
    }

    /**
     * @return \PDO
     */
    public function getDbConnection(): \PDO
    {
        if ($this->dbConnection === null) {
            $parameters = $this->getParameters('db');

            $this->dbConnection = new \PDO(
                $parameters['hostname'],
                $parameters['username'],
                $parameters['password']
            );
        }

        $this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $this->dbConnection;
    }
}