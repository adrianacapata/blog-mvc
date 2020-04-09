<?php

namespace Blog\Controller;

use Blog\Model\Repository\CategoryRepository;

class BlogController
{

    public function indexAction()
    {
        exit('bello');
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