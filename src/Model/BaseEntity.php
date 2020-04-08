<?php

namespace Blog\Model;

use Blog\DependencyInjection\Container;

class BaseEntity
{
    public function getConnection()
    {
        $params = include __DIR__ . '/../../config/parameters.php';

        $di = new Container($params);
        return $di->getDbConnection();
    }
}