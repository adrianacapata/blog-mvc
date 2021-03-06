<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Helper\TemplateHelper;
use Blog\Model\Entity\BlogEntity;
use Blog\Model\Repository\BlogRepository;
use Blog\Router\Response\Response;
use Blog\Validator\SearchValidator;

class ArchiveController
{
    /**
     *
     * @return Response
     */
    public function listAction(): Response
    {
        $request = Container::getRequest();
        $queryParameters = $request->getQueryParameters();

        //pagination
        $page = filter_var($queryParameters['page'] ?? 1, FILTER_VALIDATE_INT);
        //nr of posts on a page
        $limit = BlogEntity::NR_OF_POSTS_ON_PAGE;
        //start position
        $offset = ($page - 1) * $limit;

        //validate input word
        $validator = new SearchValidator($request);
        $searchedWord = $validator->validate();
        /** @var BlogRepository $blogRepository */
        $blogRepository = Container::getRepository(BlogRepository::class);

        $status = Response::HTTP_STATUS_OK;
        $errors = '';
        //word is not valid
        if (!$searchedWord) {
            $errors = $validator->getValidationErrors();
            $status = $errors ? Response::HTTP_STATUS_BAD_REQUEST : Response::HTTP_STATUS_OK;
        }
        $totalPages = (int)ceil($blogRepository->searchCount($searchedWord) / $limit);

        //search input true
        return new Response(
            'archive' . DIRECTORY_SEPARATOR . 'list.php',
            [
                'posts' => $blogRepository->searchResult($limit, $offset, $searchedWord),
                'searchedWord' => $searchedWord,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'pagination' => TemplateHelper::pagination($totalPages, $page),
                'url' => $request->getBaseUrl() . '?' . http_build_query(['q' => $searchedWord]),
                'errorMessages' => $errors,
            ],
            $status
        );
    }

}