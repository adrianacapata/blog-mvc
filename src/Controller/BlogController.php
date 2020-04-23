<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Helper\TemplateHelper;
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

    public function categoryAction()
    {
        $request = Container::getRequest();
        $categoryId = $request->getQueryParameters()['id'];
        $page = (int) $request->getQueryParameters()['page'];

        //nr of blogs on a page
        $limit = 2;
        //start position
        $offset = ($page - 1)*$limit;
        $totalPages = (int) ceil(BlogRepository::getAllBlogsByCategoryId($categoryId) / $limit);

        $blogs = BlogRepository::findBlogByCategoryId($categoryId, $limit, $offset);
        return new Response('blog\blog_by_category.php', [
            'blogs' => $blogs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'pagination' => TemplateHelper::pagination($totalPages, $page),
            'url' => TemplateHelper::createUrl(),
        ]);
    }
}