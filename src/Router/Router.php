<?php

namespace Blog\Router;

use Blog\DependencyInjection\Container;
use Blog\Router\Exception\NotFoundException;

class Router
{
    private const CONTROLLER_NAMESPACE = "Blog\\Controller\\";

    private $request;
    private $queryParameters;
    private $postParameters;
    private $files;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->request = Container::getRequest();
    }

    /**
     * @return Response
     * @throws NotFoundException
     */
    public function request()
    {
        // TODO: move to the request to init its parameters based on the http request data ($_ globals)

        $FQNController = self::CONTROLLER_NAMESPACE . $this->request->getControllerName() . 'Controller';

        if (!class_exists($FQNController)) {
            throw new NotFoundException("Class $FQNController not found");
        }

        $action = $this->request->getActionName() . 'Action';
        $controller = new $FQNController();

        if (!method_exists($controller, $action)) {
            throw new NotFoundException("Method $action does not exist in $FQNController");
        }

        return $controller->$action();
    }

    /**
     * @return mixed
     */
    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    /**
     * @return mixed
     */
    public function getPostParameters()
    {
        return $this->postParameters;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

}