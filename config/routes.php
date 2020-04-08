<?php

use Blog\Controller\BlogController;

$routes = [];

return $routes = [
    'index' => [
        'path:' => '/',
        'controller:' => BlogController::class
    ],


];