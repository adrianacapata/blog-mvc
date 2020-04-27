<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Helper\TemplateHelper;
use Blog\Model\Repository\BlogRepository;
use Blog\Model\Repository\CategoryRepository;
use Blog\Model\Repository\CommentRepository;
use Blog\Router\Response\JSONResponse;
use Blog\Router\Response\Response;

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

    public function detailAction()
    {
        $request = Container::getRequest();
        $blogId = (int) $request->getQueryParameters()['id'];

        return new Response('blog\show.php', [
            'blog' => BlogRepository::findOneById($blogId),
            'comments' => CommentRepository::getCommentsByBlogId($blogId),
        ]);
    }

    public function likeAction()
    {
        $request = Container::getRequest();
        $blogId = $request->getQueryParameters()['blog_id'];
        try {
            BlogRepository::incrementLikeCountByBlogId($blogId);
            $status = 'success';
        } catch (\Exception $e) {
            $status = 'failed';
        }

        return new JSONResponse([
            'status' => $status,
        ]);
    }

    public function dislikeAction()
    {
        $request = Container::getRequest();
        $blogId = $request->getQueryParameters()['blog_id'];
//        try cactch
        try {
            BlogRepository::incrementDislikeCountByBlogId($blogId);
            $status = 'success';
        } catch (\Exception $e) {
            $status = 'failed';
        }

        return new JSONResponse([
            'status' => $status,
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