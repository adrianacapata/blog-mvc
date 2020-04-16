<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
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
        return new Response('category\show.php', [
            'categoriesTree' => CategoryRepository::getCategoryTree(),
            'popularBlogs' => BlogRepository::getPopularity(),
        ]);
    }

    public function categoriesAction()
    {

        $categories = CategoryRepository::fetchCategories();
echo '<pre>'; var_dump($categories); exit();

    }

    public function categoryAction()
    {
        $request = Container::getRequest();
        $categoryId = $request->getQueryParameters()['id'];
        $page = $request->getQueryParameters()['page'];
        $limit = 2;

        $offset = ($page == 1) ? 0 : $page*$limit - 1;

        return new Response('category\blog_by_category.php', [
            'blogs' => BlogRepository::findBlogByCategoryId($categoryId, $offset, $limit)
        ]);
    }

}