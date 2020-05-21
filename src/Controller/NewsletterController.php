<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Model\Repository\NewsletterRepository;
use Blog\Router\Response\JSONResponse;
use Blog\Router\Response\Response;
use Blog\Validator\SubscribeValidator;
use Blog\Validator\UnsubscribeValidator;
use Exception;

class NewsletterController
{
    /**
     * saves input email to send the newsletter to it
     * @return JSONResponse
     */
    public function subscribeAction(): JSONResponse
    {
        //check if POST else 404
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return new JSONResponse([
                'errors' => [
                    'global' => 'error occurred when creating the comment'
                ],
            ], Response::HTTP_STATUS_NOT_FOUND);
        }

        $validator = new SubscribeValidator(Container::getRequest());
        $newsletterEntity = $validator->validate();

        if ($newsletterEntity) {
            try {
                NewsletterRepository::addSubscriber($newsletterEntity);

                return new JSONResponse();
            } catch (Exception $e) {
                $errors = [
                    'global' => 'error occurred while inserting into database',
                ];
            }
        } else {
            $errors = $validator->getValidationErrors();
        }

        return new JSONResponse([
            'errors' => $errors,
        ], Response::HTTP_STATUS_BAD_REQUEST);
    }

    //TODO create a validation class for email - refactor this action
    public function unsubscribeAction(): Response
    {
        $validator = new UnsubscribeValidator(Container::getRequest());
        $newsletterEntity = $validator->validate();

        if ($newsletterEntity) {
            //treat validation case
            $newsletter = NewsletterRepository::findOneByEmail($newsletterEntity->getEmail());
            if (!$newsletter) {
                return new Response(
                    'pageNotFound.php',
                    [
                        'message' => 'email does not exist in database'
                    ],
                    Response::HTTP_STATUS_NOT_FOUND
                );
            }

            NewsletterRepository::removeSubscriber($newsletter);

            return new Response('/subscription/unsubscribe.php');
        }
        $errors = $validator->getValidationErrors();

        return new Response(
            'pageNotFound.php',
            [
                'message' => $errors,
            ],
            Response::HTTP_STATUS_BAD_REQUEST
        );
    }
}