<?php

namespace Blog\Router;

use Blog\DependencyInjection\Container;
use Blog\Router\Exception\HTTPNotFoundException;
use Blog\Router\Response\ResponseInterface;

class Router
{
    private const CONTROLLER_NAMESPACE = "Blog\\Controller\\";

    /** @var Request $request */
    private $request;
    /** @var Router */
    private static $instance;

    /**
     * Router constructor.
     */
    private function __construct()
    {
        $this->request = Container::getRequest();
    }

    /**
     * @return Router
     */
    public static function getInstance(): Router
    {
        if (!isset(self::$instance)) {
            self::$instance = new Router();
        }

        return self::$instance;
    }
    
    /**
     * @throws HTTPNotFoundException
     */
    public function request(): ResponseInterface
    {
        $FQNController = self::CONTROLLER_NAMESPACE . $this->request->getControllerName() . 'Controller';

        if (!class_exists($FQNController)) {
            throw new HTTPNotFoundException("Class $FQNController not found");
        }

        $action = $this->request->getActionName() . 'Action';
        $controller = new $FQNController();

        if (!method_exists($controller, $action)) {
            throw new HTTPNotFoundException("Method $action does not exist in $FQNController");
        }

        return $controller->$action();
    }
}