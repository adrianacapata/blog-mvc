<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Model\Repository\CommentRepository;
use Blog\Router\Response\JSONResponse;
use Blog\Router\Response\Response;
use Blog\Validator\CommentValidator;
use Exception;

class CommentController
{
    public function addAction(): JSONResponse
    {
        //check if POST else 404
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return new JSONResponse([
                'errors' => [
                    'global' => 'error occurred when creating the comment'
                ],
            ], Response::HTTP_STATUS_NOT_FOUND);
        }

        $validator = new CommentValidator(Container::getRequest());
        $commentEntity = $validator->validate();
        if ($commentEntity) {
            try {
                CommentRepository::addCommentToBlogId($commentEntity);

                return new JSONResponse([
                    'author' => $commentEntity->getAuthorName(),
                    'comment' => $commentEntity->getContent(),
                    'date' => $commentEntity->getCreatedAt(),
                ]);
            } catch (Exception $e) {
                $errors = [
                    'global' => 'error occurred when creating the comment'
                ];
            }
        } else {
            $errors = $validator->getValidationErrors();
        }

        return new JSONResponse([
            'errors' => $errors,
        ], Response::HTTP_STATUS_BAD_REQUEST);
    }
}