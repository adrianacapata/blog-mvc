<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Helper\TemplateHelper;
use Blog\Model\Repository\BlogRepository;
use Blog\Router\Response\Response;
use Blog\Validator\SearchValidator;

class ArchiveController
{
    //TODO pagination
    public function listAction(): Response
    {
        $request = Container::getRequest();
        $queryParameters = $request->getQueryParameters();

        //pagination
        $page = filter_var($queryParameters['page'] ?? 1, FILTER_VALIDATE_INT);
        //nr of posts on a page
        $limit = 2;
        //start position
        $offset = ($page - 1)*$limit;
        $totalPages = (int) ceil(BlogRepository::getArchivedBlogsCount() / $limit);

        //validate input word
        $validator = new SearchValidator($request);
        $searchedWord = $validator->validate();

        //word is not valid
        if ($validator->getValidationErrors()) {
            return new Response(
                '\archive\search_results.php',
                [
                    'message' => $validator->getValidationErrors()
                ],
                Response::HTTP_STATUS_BAD_REQUEST);
        }

        if (!is_null($searchedWord)) {
            //search input true
            return new Response(
                '\archive\search_results.php',
                [
                    'posts' => BlogRepository::searchResult($searchedWord, $limit, $offset),
                    'searchedWord' => $searchedWord,
                    'currentPage' => $page,
                    'totalPages' => $totalPages,
                    'pagination' => TemplateHelper::pagination($totalPages, $page),
                    'url' => TemplateHelper::createUrl(),
                ],
                Response::HTTP_STATUS_OK
            );

        }

        //no search was performed
        return new Response('\archive\list.php', [
            'posts' => BlogRepository::getArchivedBlogs($limit, $offset),
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'pagination' => TemplateHelper::pagination($totalPages, $page),
            'url' => TemplateHelper::createUrl(),
        ]);
    }

    //TODO use a single action - just list - check if search word exists
    public function searchResultAction(): Response
    {
        $request = Container::getRequest();
        $validator = new SearchValidator($request);
        $searchedWord = $validator->validate();

        if ($searchedWord) {
            $searchCount = BlogRepository::searchCount($searchedWord);
            if ($searchCount > 0) {
                $page = filter_var($request->getQueryParameters()['page'] ?? 1, FILTER_VALIDATE_INT);
                //nr of blogs on a page
                $limit = 2;
                //start position
                $offset = ($page - 1)*$limit;
                $totalPages = (int) ceil($searchCount/ $limit);

                return new Response(
                    '\archive\search_results.php',
                    [
                        'posts' => BlogRepository::searchResult($searchedWord, $limit, $offset),
                        'searchedWord' => $searchedWord,
                        'currentPage' => $page,
                        'totalPages' => $totalPages,
                        'pagination' => TemplateHelper::pagination($totalPages, $page),
                        'url' => TemplateHelper::createUrl(),
                    ],
                    Response::HTTP_STATUS_OK
                );
            }
            $errors = [
                'searched_word' => 'no results'
            ];
        } else {
            $errors = $validator->getValidationErrors();
        }

        return new Response(
            'pageNotFound.php',
            [
                'message' => $errors
            ],
            Response::HTTP_STATUS_BAD_REQUEST);
    }
}