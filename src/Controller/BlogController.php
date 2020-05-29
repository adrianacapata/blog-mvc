<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Model\Repository\BlogRepository;
use Blog\Model\Repository\CategoryRepository;
use Blog\Model\Repository\CommentRepository;
use Blog\Router\Exception\HTTPNotFoundException;
use Blog\Router\Response\JSONResponse;
use Blog\Router\Response\Response;
use Exception;

class BlogController
{
    public function indexAction(): Response
    {
        /** @var CategoryRepository $repository */
        $repository = Container::getRepository(CategoryRepository::class);

        return new Response('category' . DIRECTORY_SEPARATOR . 'show.php', [
            'categoryTree' => $repository->getCategoryTree(),
            'popularBlogs' => BlogRepository::getPopularity(),
        ]);
    }

    /**
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

    public function likeAction(): JSONResponse
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

    public function dislikeAction(): JSONResponse
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