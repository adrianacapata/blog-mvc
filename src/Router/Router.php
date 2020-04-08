<?php

namespace Blog\Router;

use Blog\DependencyInjection\ContainerInterface;
use Blog\Router\Exception\NotFoundException;

class Router
{
    private const CONTROLLER_NAMESPACE = "Blog\\Controller\\";

    private $container;

    /**
     * Router constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private function getUriFirstSegment($urlElements)
    {
        return explode('/', $urlElements['path']);
    }

    private function getQueryParams($urlElements)
    {
        return $urlElements['query'] ?? '';

    }
    /**
     * @param string $url
     * @return mixed
     * @throws NotFoundException
     */
    public function request(string $url)
    {
        $urlElements = parse_url($url);

        $pathElements = $this->getUriFirstSegment($urlElements);

        $params = $this->getQueryParams($urlElements);

        $FQNController = self::CONTROLLER_NAMESPACE . ucfirst($pathElements[1]) . 'Controller';

        if (!class_exists($FQNController)) {
            throw new NotFoundException("Class $FQNController not found");
        }

        $action = (empty($pathElements[2]) ? 'index' : $pathElements[2]) . 'Action';

        $controller = new $FQNController();

        if (!method_exists($controller, $action)) {
            throw new NotFoundException("Method $action does not exist in $FQNController");
        }

        return $controller->$action($params);
    }

}