<?php

require_once __DIR__.'/../vendor/autoload.php';

use Blog\Router\Router;

$router = Router::getInstance();
$response = $router->request();
$response->render();


