<?php

namespace Blog\Controller;

use Blog\Model\Repository\BlogRepository;
use Blog\Model\Repository\CategoryRepository;
use Blog\Router\Response;

class BlogController
{

    /**
     * @return Response
     */
    public function indexAction()
    {
        $categories = CategoryRepository::fetchCategories();
        $categoriesTree = CategoryRepository::getCategoryTree();
        $popularity = BlogRepository::getPopularity();

        return new Response('category\show.php', [
            'categories' => $categories,
            'categoriesTree' => $categoriesTree,
            'popularBlogs' => $popularity
        ]);
    }

    public function categoriesAction()
    {

        $categories = CategoryRepository::fetchCategories();
echo '<pre>'; var_dump($categories); exit();

    }

    public function categoryAction()
    {
        $category = CategoryRepository::findOneById(3);
//        return new Response(templateName, [variableName => value]);

        echo '<pre>'; var_dump($category); exit();
    }

}