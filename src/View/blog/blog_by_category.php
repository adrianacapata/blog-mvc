<?php
/**
 * @var BlogEntity[] $blogs
 * @var \Blog\Model\Entity\CategoryEntity $category
 * @var int $currentPage
 * @var int $totalPages
 * @var $pagination[]
 * @var string $url
 *
 */

use Blog\Model\Entity\BlogEntity; ?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link href="/public/css/style.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>
    <div class="page-header">
        <h1>Blogs by category: <?=$category->getName()?></h1>
    </div>

    <?php if (!empty($blogs)): ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Publication Date</th>
                    <th>Likes</th>
                    <th>Dislikes</th>
                    <th>Comments nummber</th>
                </tr>
            </thead>
        <tbody>
            <?php foreach ($blogs as $blog): ?>
                <tr>
                    <td><?= $blog->getTitle() ?></td>
                    <td><?= $blog->getCreatedAt() ?></td>
                    <td><?= $blog->getLikeCount() ?></td>
                    <td><?= $blog->getDislikeCount() ?></td>
                    <td><?= $blog->getCommentNr() ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        there are no posts in this category
    <?php endif ?>

    <div class="pagination">
        <label><b>Current Page: <?= $currentPage . '/' . $totalPages ?></b></label><br />
        <label>
            <?php if ($currentPage > 1): ?>
                <a href="<?= $url . '?id=' . $category->getId() .  '&page=' . ($currentPage - 1)?>">&lt;</a>
            <?php endif ?>
                <?php foreach ($pagination as $pag): ?>
                    <?php if ($currentPage !== $pag): ?>
                        <?php if ($pag === '...'): ?>
                            <span><?=$pag?></span>
                        <?php else: ?>
                            <a href="<?= $url . '?id=' . $category->getId() .  '&page=' . $pag ?>"><?=$pag?></a>
                        <?php endif ?>
                    <?php endif ?>
                    <?php if ($currentPage === $pag): ?>
                        <span style="color: red;"><?=$pag?></span>
                    <?php endif ?>
                <?php endforeach; ?>
                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?=$url . '?id=' . $category->getId() . '&page=' . ($currentPage + 1)?>">&gt;</a>
                <?php endif ?>
        </label>
    </div>
</body>
</html>
