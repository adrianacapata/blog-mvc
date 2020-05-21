<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Model\Entity\CategoryEntity;
use Blog\Model\Repository\BlogRepository;
use Blog\Model\Repository\CategoryRepository;
use Blog\Model\Repository\CommentRepository;
use Blog\Router\Exception\HTTPNotFoundException;
use Blog\Router\Response\JSONResponse;
use Blog\Router\Response\Response;
use Exception;

class BlogController
{

    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        $cache = Container::getCache();

        $categoryTree = $cache->get(CategoryEntity::CATEGORY_TREE_FROM_CACHE);

        //check if already exists in cache - if not add it to cache and retrieve from there
        if (!$categoryTree) {
            $categoryTree = CategoryRepository::getCategoryTree();
            $cache->add(CategoryEntity::CATEGORY_TREE_FROM_CACHE, $categoryTree);
        }

        return new Response('category\show.php', [
            'categoryTree' => $categoryTree,
            'popularBlogs' => BlogRepository::getPopularity(),
        ]);
    }

    /**
     * @return Response
     * @throws HTTPNotFoundException
     */
    public function detailAction(): Response
    {
        $request = Container::getRequest();
        $blogId = filter_var($request->getQueryParameters()['id'] ?? 0, FILTER_VALIDATE_INT);

        $blog = null;
        if ($blogId) {
            $blog = BlogRepository::findOneById($blogId);
        }

        if (!$blog) {
            throw new HTTPNotFoundException('Blog not found for id: ' . $blogId);
        }

        return new Response('blog\show.php', [
            'blog' => $blog,
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
        } catch (Exception $e) {
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

        try {
            BlogRepository::incrementDislikeCountByBlogId($blogId);
            $status = 'success';
        } catch (Exception $e) {
            $status = 'failed';
        }

        return new JSONResponse([
            'status' => $status,
        ]);
    }
}