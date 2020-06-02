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
     * saves input email and sends the newsletter to it
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

        /** @var NewsletterRepository $newsletterRepository */
        $newsletterRepository = Container::getRepository(NewsletterRepository::class);

        $validator = new SubscribeValidator(Container::getRequest());
        $newsletterEntity = $validator->validate();

        if ($newsletterEntity) {
            try {
                $newsletterRepository->addSubscriber($newsletterEntity);

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

    public function unsubscribeAction(): Response
    {
        $validator = new UnsubscribeValidator(Container::getRequest());
        $newsletterEntity = $validator->validate();

        /** @var NewsletterRepository $newsletterRepository */
        $newsletterRepository = Container::getRepository(NewsletterRepository::class);
        if ($newsletterEntity) {
            //treat validation case
            $newsletter = $newsletterRepository->findOneByEmail($newsletterEntity->getEmail());
            if (!$newsletter) {
                return new Response(
                    'pageNotFound.php',
                    [
                        'message' => 'email does not exist in database'
                    ],
                    Response::HTTP_STATUS_NOT_FOUND
                );
            }

            $newsletterRepository->removeSubscriber($newsletter);

            return new Response('subscription' . DIRECTORY_SEPARATOR . 'unsubscribe.php');
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