<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Model\Repository\CommentRepository;
use Blog\Router\Response\JSONResponse;

class CommentController
{
    public function addAction()
    {
        $request = Container::getRequest();
        $blogId = (int) $request->getPostParameters()['blog_id'];
        $authorName = $request->getPostParameters()['author_name'] ?? '';
        $comment = $request->getPostParameters()['comment'] ?? '';

        try {
            CommentRepository::addCommentToBlogId($blogId, $authorName, $comment);
            $status = 'success';
        } catch (\Exception $e) {
            $e->getMessage();
            $status = 'failed';
        }
        $lastComment = CommentRepository::getLastAddedCommentByBlogId($blogId);

        return new JSONResponse([
            'status' => $status,
            'author' => $lastComment->getAuthorName(),
            'comment' => $lastComment->getContent(),
            'date' => $lastComment->getCreatedAt(),
        ]);
    }
}