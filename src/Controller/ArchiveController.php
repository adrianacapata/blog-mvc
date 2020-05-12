<?php

namespace Blog\Controller;

use Blog\Model\Repository\BlogRepository;
use Blog\Router\Response\Response;

class ArchiveController
{
    public function listAction(): Response
    {
        return new Response('/archive/list.php', [
            'posts' => BlogRepository::getArchivedBlogs(),
        ]);
    }

    public function searchAction()
    {

    }
}