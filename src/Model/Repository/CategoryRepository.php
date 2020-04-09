<?php

namespace Blog\Model\Repository;

use Blog\DependencyInjection\Container;
use Blog\Model\Entity\CategoryEntity;

class CategoryRepository
{

    /**
     * @return CategoryEntity[]
     */
    public static function fetchCategories(): \ArrayObject
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->query('Select * FROM `category`');
        $stmt->execute();

        $categoriesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $categories = new \ArrayObject();
        foreach ($categoriesData as $category) {
            $categoryEntity = new CategoryEntity();
            $categoryEntity->setId($category['id']);
            $categoryEntity->setName($category['name']);
            $categoryEntity->setTreeLeft($category['tree_left']);
            $categoryEntity->setTreeRight($category['tree_right']);

            $categories->append($categoryEntity);
        }

        return $categories;
    }

    /**
     * Will return a single Category entity from db by $id
     * @param int $id
     * @return CategoryEntity
     */
    public static function findOneById(int $id): CategoryEntity
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->prepare('SELECT * FROM `category` WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $categoryData = $stmt->fetch(\PDO::FETCH_ASSOC);

        $categoryEntity = new CategoryEntity();
        $categoryEntity->setId($categoryData['id']);
        $categoryEntity->setName($categoryData['name']);
        $categoryEntity->setTreeLeft($categoryData['tree_left']);
        $categoryEntity->setTreeRight($categoryData['tree_right']);

        return $categoryEntity;
    }
}