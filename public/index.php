<?php

require_once __DIR__.'/../vendor/autoload.php';

use Blog\Router\Router;

$router = new Router();
$response = $router->request();
$response->render();


//TODO
// /blog -> BlogController/indexAction
// /blog/index
// /blog/test -> BlogController/testAction

