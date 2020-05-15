<?php

namespace Blog\Command;

use Blog\Model\Repository\BlogRepository;

class ArchiveCommand implements CommandInterface
{
    public function execute(): void
    {
        $archivePosts = BlogRepository::archiveBlogs();

        echo $archivePosts ? $archivePosts . ' posts were archived' : 'no posts were archived';
    }
}