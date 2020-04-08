<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once  __DIR__ . '/../config/parameters.php';
require_once  __DIR__ . '/../config/routes.php';

use Blog\DependencyInjection\Container;
use Blog\Router\Router;

$di = new Container($parameters);

$router = new Router($di);

$router->request($_SERVER['REQUEST_URI']);

//TODO
// /blog -> BlogController/indexAction
// /blog/index
// /blog/test -> BlogController/testAction

