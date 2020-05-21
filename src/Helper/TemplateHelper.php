<?php

namespace Blog\Helper;

use Blog\DependencyInjection\Container;
use Blog\Model\Repository\BlogRepository;

class TemplateHelper
{

    /**
     * @param $totalPages
     * @param $currentPage
     * @return array
     */
    public static function pagination($totalPages, $currentPage): array
    {
        $pagination = [];
        if ($totalPages >= 5) {
            $pagination = [1];
            if ($currentPage === 1 || $currentPage === 2) {
                $pagination[] = 2;
                $pagination[] = 3;
            }
            if ($currentPage - 1 >= 3) {
                $pagination[] = '...';
            }
            if ($currentPage >= 3 && $currentPage <= $totalPages - 2) {
                $pagination[] = $currentPage - 1;
                $pagination[] = $currentPage;
                $pagination[] = $currentPage + 1;
            }
            if ($totalPages - $currentPage >= 3) {
                $pagination[] = '...';
            }
            if ($currentPage === $totalPages - 1) {
                $pagination[] = $currentPage - 1;
                $pagination[] = $currentPage;
            }
            if ($currentPage === $totalPages) {
                $pagination[] = $currentPage - 2;
                $pagination[] = $currentPage - 1;
            }
            $pagination[] = $totalPages;
        } else {
            for ($i = 1; $i <= $totalPages; $i++) {
                $pagination[] = $i;
            }
        }

        return $pagination;
    }

    /**
     * @return string
     */
    public static function createUrl(): string
    {
        $request = Container::getRequest();

        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();

        return '/' . $controllerName . '/' . $actionName;
    }

    public static function getUrl($baseUrl, $searchedWord, $page)
    {
        return $baseUrl . '?' . http_build_query([
            'q' => $searchedWord,
            'page' => $page
            ]);
    }

    /**
     * @param string|null $searchedWord
     * @param int $limit
     * @return bool|int
     */
    public static function getTotalPagesForSearch(?string $searchedWord, int $limit)
    {
        $cache = Container::getCache();
        if ($cache->get('count')) {
            $totalPages = (int)ceil($cache->get('count') / $limit);
        } else {
            $totalPages = $cache->add('count',(int)ceil(BlogRepository::searchCount($searchedWord) / $limit));
        }

        return $totalPages;
    }
}