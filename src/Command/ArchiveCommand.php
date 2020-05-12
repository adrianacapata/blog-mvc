<?php

namespace Blog\Command;

use Blog\Model\Repository\BlogRepository;

class ArchiveCommand implements CommandInterface
{
    //TODO show how many posts were archived
    public function execute(): void
    {
        $archivePosts = BlogRepository::archiveBlogs();

        echo $archivePosts ? $archivePosts . ' posts were archived' : 'no posts were archived';
    }
}