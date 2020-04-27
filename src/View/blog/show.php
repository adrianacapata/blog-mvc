<?php
/**
 * @var \Blog\Model\Entity\BlogEntity $blog
 * @var \Blog\Model\Entity\CommentEntity[] $comments[]
 */

use Blog\Helper\TemplateHelper; ?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="/css/style.css" rel="stylesheet">
    <script src="/js/blog_show.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>
    <body>
        <div class="container-fluid">
            <div class="blog-header page-header">
                <div class="blog-header-title panel-title"><?=$blog->getTitle()?></div>
                <div class="blog-header-like">
                    <label>Likes: </label>
                    <span id="like-count"><?=$blog->getLikeCount()?></span>
                </div>
                <div class="blog-header-dislike dislike-count">
                    <label>Dislikes: </label>
                    <span id="dislike-count"><?=$blog->getDislikeCount()?></span>
                </div>
                <div class="blog-header-comment>">Comments <?=$blog->getCommentNr()?></div>
            </div>

            <div class="blog-content">
                <?=$blog->getContent()?>
            </div>

            <div class="blog-appreciation">
                <button class="like" data-id="<?=$blog->getId()?>" >Like</button>
                <button class="dislike" data-id="<?=$blog->getId()?>">Dislike</button>
            </div>

            <div class="blog-comment">
                <duv id="show-comment">

                </duv>
                <?php foreach ($comments as $comment): ?>
                    <label><?=$comment->getAuthorName()?> at <?=$comment->getCreatedAt()?></label>
                    <p><?=$comment->getContent()?></p>
                <?php endforeach ?>
            </div>

            <div class="blog-add-comment"> Add comment
                <form method="POST" action="/comment/add" id="add-comment">
                    <input type="hidden" name="blog_id" value="<?=$blog->getId()?>"/>
                    <div class="comment-author">
                        <input type="text" name="author_name" id="author_name" class="form-control" placeholder="Author name" />
                    </div>
                    <div class="comment-content">
                        <textarea name="comment" id="comment" class="form-control" placeholder="comment..." rows="5"> </textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" id="submit" class="btn btn-info"> Add comment </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
