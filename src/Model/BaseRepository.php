<?php

namespace Blog\Model;

class BaseRepository
{
    /**
     * @var BaseEntity
     */
    private $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}