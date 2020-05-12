<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Model\Repository\NewsletterRepository;
use Blog\Router\Response\JSONResponse;
use Blog\Router\Response\Response;
use Blog\Validator\SubscribeValidator;
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
        $blogSubscribeEntity = $validator->validate();

        if ($blogSubscribeEntity) {
            try {
                NewsletterRepository::addSubscriber($blogSubscribeEntity);

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
    public function unsubscribeAction()
    {
        $request = Container::getRequest();
        $email = $request->getQueryParameters()['email'] ?? null;

        if (!$email) {
            return new Response(
                'pageNotFound.php',
                [
                    'message' => 'email not provided'
                ],
                Response::HTTP_STATUS_NOT_FOUND
            );
        }
        //email ok
        $newsletterEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        if ($newsletterEmail) {
            //treat validation case
            $newsletter = NewsletterRepository::findOneByEmail($newsletterEmail);
            if (!$newsletter) {
                return new Response(
                    'pageNotFound.php',
                    [
                        'message' => 'email does not exist'
                    ],
                    Response::HTTP_STATUS_NOT_FOUND
                );
            }

            NewsletterRepository::removeSubscriber($newsletter);

            return new Response('/subscription/unsubscribe.php');
        }

        return new Response(
            'pageNotFound.php',
            [
                'message' => 'email is not valid'
            ],
            Response::HTTP_STATUS_BAD_REQUEST
        );
    }
}