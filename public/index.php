<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'vendor'. DIRECTORY_SEPARATOR . 'autoload.php';

use Blog\Router\Exception\HTTPNotFoundException;
use Blog\Router\Response\Response;
use Blog\Router\Router;

$router = Router::getInstance();
try {
    $response = $router->request();
} catch (HTTPNotFoundException $e) {
    $response = new Response('pageNotFound.php', ['message' => $e->getMessage()], Response::HTTP_STATUS_NOT_FOUND);
}
$response->render();

