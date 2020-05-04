<?php

namespace Blog\Controller;

use Blog\DependencyInjection\Container;
use Blog\Helper\TemplateHelper;
use Blog\Model\Repository\BlogRepository;
use Blog\Model\Repository\CategoryRepository;
use Blog\Router\Exception\HTTPNotFoundException;
use Blog\Router\Response\Response;

class CategoryController
{
    /**
     * @return Response
     * @throws HTTPNotFoundException
     */
    public function detailAction(): Response
    {
        $request = Container::getRequest();
        $queryParameters = $request->getQueryParameters();
        $categoryId = filter_var($queryParameters['id'] ?? 0, FILTER_VALIDATE_INT);

        $category = null;
        if ($categoryId) {
            $category = CategoryRepository::findOneById($categoryId);
        }

        if (!$category) {
            throw new HTTPNotFoundException('Category not found for id: ' . $categoryId);
        }

        $page = filter_var($queryParameters['page'] ?? 1, FILTER_VALIDATE_INT);
        //nr of blogs on a page
        $limit = 2;
        //start position
        $offset = ($page - 1)*$limit;
        $totalPages = (int) ceil(BlogRepository::countBlogsByCategoryId($categoryId) / $limit);
        $blogs = BlogRepository::findBlogsByCategoryId($categoryId, $limit, $offset);

        return new Response('blog\blog_by_category.php', [
            'blogs' => $blogs,
            'category' => $category,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'pagination' => TemplateHelper::pagination($totalPages, $page),
            'url' => TemplateHelper::createUrl(),
        ]);
    }
}