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
            $categoryEntity->setLevel($category['level']);

            $categories->append($categoryEntity);
        }

        return $categories;
    }

    /**
     *
     * @return array [
     *   $anyKey = > [
     *      'name' => 'Categ 1',
     *      'level' => 1,
     *      'posts' => 0,
     *   ]
     * ]
     */
    public static function getCategoryTree()
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->query(
            'SELECT c.name, COUNT(p.name) `level`,  GROUP_CONCAT(p.name), (SELECT COUNT(id) FROM blog WHERE category_id = c.id) as posts
            FROM category c
            
            INNER JOIN category p ON c.tree_left >= p.tree_left AND c.tree_right <= p.tree_right
            GROUP BY c.name
            ORDER BY c.tree_left'
        );

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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