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
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = Container::getRepository(CategoryRepository::class);
        /** @var BlogRepository $blogRepository */
        $blogRepository = Container::getRepository(BlogRepository::class);

        return new Response('category' . DIRECTORY_SEPARATOR . 'show.php', [
            'categoryTree' => $categoryRepository->getCategoryTree(),
            'popularBlogs' => $blogRepository->getPopularity(),
        ]);
    }

    /**
     * @throws HTTPNotFoundException
     */
    public function detailAction(): Response
    {
        $request = Container::getRequest();
        $blogId = filter_var($request->getQueryParameters()['id'] ?? 0, FILTER_VALIDATE_INT);

        /** @var BlogRepository $blogRepository */
        $blogRepository = Container::getRepository(BlogRepository::class);
        /** @var CommentRepository $commentRepository */
        $commentRepository = Container::getRepository(CommentRepository::class);

        $blog = null;
        if ($blogId) {
            $blog = $blogRepository->findOneById($blogId);
        }

        if (!$blog) {
            throw new HTTPNotFoundException('Blog not found for id: ' . $blogId);
        }

        return new Response('blog' . DIRECTORY_SEPARATOR . 'show.php', [
            'blog' => $blog,
            'comments' => $commentRepository->getCommentsByBlogId($blogId),
        ]);
    }

    public function likeAction(): JSONResponse
    {
        $request = Container::getRequest();
        $blogId = $request->getQueryParameters()['blog_id'];

        /** @var BlogRepository $blogRepository */
        $blogRepository = Container::getRepository(BlogRepository::class);

        try {
            $blogRepository->incrementLikeCountByBlogId($blogId);
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

        /** @var BlogRepository $blogRepository */
        $blogRepository = Container::getRepository(BlogRepository::class);

        try {
            $blogRepository->incrementDislikeCountByBlogId($blogId);
            $status = 'success';
        } catch (Exception $e) {
            $status = 'failed';
        }

        return new JSONResponse([
            'status' => $status,
        ]);
    }
}