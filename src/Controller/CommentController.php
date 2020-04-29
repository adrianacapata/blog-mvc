<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Model\Repository\CommentRepository;
use Blog\Router\Exception\HTTPNotFoundException;
use Blog\Router\Response\JSONResponse;
use Blog\Router\Response\Response;
use Blog\Validator\CommentValidator;

class CommentController
{
    private const SUCCESS = 'success';
    private const FAILED = 'failed';

    public function addAction()
    {
        //check if POST else 404
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new HTTPNotFoundException('only post requests allowed');
        }

        $status = self::FAILED;
        $validator = new CommentValidator(Container::getRequest());
        $commentEntity = $validator->validate();
        if ($commentEntity) {
            try {
                $status = self::SUCCESS;
                CommentRepository::addCommentToBlogId($commentEntity);

                return new JSONResponse([
                    'status' => $status,
                    'author' => $commentEntity->getAuthorName(),
                    'comment' => $commentEntity->getContent(),
                    'date' => $commentEntity->getCreatedAt(),
                ]);
            } catch (\Exception $e) {
                $status = self::FAILED;
                $errors = [
                    'global' => 'error occurred when creating the comment'
                ];
            }
        } else {
            $errors = $validator->getValidationErrors();
        }

        return new JSONResponse([
            'status' => $status,
            'err' => $errors,
        ]);
    }
}