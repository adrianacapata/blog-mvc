<?php

namespace Blog\Router;

use Blog\DependencyInjection\Container;
use Blog\Router\Exception\HTTPNotFoundException;

class Router
{
    private const CONTROLLER_NAMESPACE = "Blog\\Controller\\";

    private $request;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->request = Container::getRequest();
    }

    /**
     * @return Response
     * @throws HTTPNotFoundException
     */
    public function request()
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