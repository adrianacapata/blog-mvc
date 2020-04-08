<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once  __DIR__ . '/../config/parameters.php';
require_once  __DIR__ . '/../config/routes.php';

use Blog\Router\Router;

$router = new Router();
$router->request();

//TODO
// /blog -> BlogController/indexAction
// /blog/index
// /blog/test -> BlogController/testAction

