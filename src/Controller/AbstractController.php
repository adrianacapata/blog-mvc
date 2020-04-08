<?php

namespace Blog\Controller;

use Blog\DependencyInjection\ContainerInterface;
use Blog\Router\Exception\NotFoundException;
use Blog\Router\Router;

abstract class AbstractController
{
    protected $container;

    protected function getEntity(string $entity)
    {
        $FQNentity = "Blog\\Model\\Entity\\" . ucfirst($entity) . 'Entity';
        if (!class_exists($FQNentity)) {
            throw new NotFoundException('entity does not exist');
        }
        return new $FQNentity();
    }

    /**
     * @param ContainerInterface $container
     * @return ContainerInterface|null
     */
    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
       return $this->container = $container;
    }

    /**
     * @param Router $router
     */
    public function setRoute(Router $router)
    {

    }
}