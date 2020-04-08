<?php

namespace Blog\Controller;

use Blog\Model\Repository\CategoryRepository;

class BlogController extends AbstractController
{

    public function testAction()
    {
        $entity = $this->getEntity('category');
        var_dump($entity->get());
    }

    public function indexAction()
    {
        exit('bello');
    }

    public function categoriesAction()
    {
        $categories = new CategoryRepository();

        return $categories->getCategories();
    }

}