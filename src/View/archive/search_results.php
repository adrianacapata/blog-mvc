<?php
/** @var BlogEntity[] $posts
 * @var string $searchedWord
 * @var string $messages
 * @var int $currentPage
 * @var int $totalPages
 * @var $pagination[]
 * @var string $url
 */

use Blog\Model\Entity\BlogEntity;
use Blog\Model\Repository\BlogRepository;

?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link href="/css/style.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body class="container-fluid">
    <div class="blog-header">
        <div class="pull-left blog-header-title">You searched for: <?=$searchedWord?></div>
        <div class="pull-right search">
            <?php if ($messages): ?>
                <?= $messages?>
            <?php endif;?>
            <form action="/archive/searchResult" method="get" id="search">
                <input type="text" id="searched_word" name="q" />
                <button type="submit" id="submit" class="btn btn-info">Search</button>
            </form>
        </div>
    </div>
    <div class="list-group results">
        <?php if (BlogRepository::searchCount($searchedWord) < 1): ?>
            <p>No results found</p>
        <?php endif;?>
        <?php foreach ($posts as $post): ?>
            <div class="list-group-item"><?=$post->getTitle()?>
                <div><?=$post->getAuthorName()?></div>
                <div><?=$post->getContent()?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="pagination">
        <label><b>Current Page: <?= $currentPage . '/' . $totalPages ?></b></label><br />
        <label>
            <?php if ($currentPage > 1): ?>
                <a href="<?= $url . '?q=' . $searchedWord.  '&page=' . ($currentPage - 1)?>">&lt;</a>
            <?php endif ?>
            <?php foreach ($pagination as $pag): ?>
                <?php if ($currentPage !== $pag): ?>
                    <?php if ($pag === '...'): ?>
                        <span><?=$pag?></span>
                    <?php else: ?>
                        <a href="<?= $url . '?q=' . $searchedWord . '&page=' . $pag ?>"><?=$pag?></a>
                    <?php endif ?>
                <?php endif ?>
                <?php if ($currentPage === $pag): ?>
                    <span style="color: red;"><?=$pag?></span>
                <?php endif ?>
            <?php endforeach; ?>
            <?php if ($currentPage < $totalPages): ?>
                <a href="<?=$url . '?q=' . $searchedWord . '&page=' . ($currentPage + 1)?>">&gt;</a>
            <?php endif ?>
        </label>
    </div>
</body>
</html>
