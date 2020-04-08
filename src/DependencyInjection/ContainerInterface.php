<?php

namespace Blog\DependencyInjection;

interface ContainerInterface
{
    public function getDbConnection();
}