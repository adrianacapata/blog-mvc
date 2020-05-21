<?php

namespace Blog\Model\Entity;

/**
 * Class CategoryEntity
 * @package Blog\Model\Entity
 */
class CategoryEntity
{

    public const CATEGORY_TREE_FROM_CACHE = 'categoryTree';

    /** @var int */
    private $id;

    /** @var int */
    private $tree_left;

    /** @var int */
    private $tree_right;

    /** @var int */
    private $name;

    /**
     * @var
     */
    private $level;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTreeLeft(): int
    {
        return $this->tree_left;
    }

    /**
     * @param int $tree_left
     */
    public function setTreeLeft(int $tree_left): void
    {
        $this->tree_left = $tree_left;
    }

    /**
     * @return int
     */
    public function getTreeRight(): int
    {
        return $this->tree_right;
    }

    /**
     * @param string $tree_right
     */
    public function setTreeRight(int $tree_right): void
    {
        $this->tree_right = $tree_right;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

}