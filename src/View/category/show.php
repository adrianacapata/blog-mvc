<?php

/**
 * @var \Blog\Model\Entity\BlogEntity[] $categoriesTree
 * @var \Blog\Model\Entity\BlogEntity[] $popularBlogs
 */
?>

    <!-- Bootstrap -->
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
    <div class="container">
        <div class="page-header">
            <h1>Categories and blogs</h1>
        </div>
        <div>
            <?php foreach ($categoriesTree as $category): ?>
                <div>
                    <?php
                        if ($category['level'] > 1) {
                            for ($i = 1; $i < $category['level']; $i++) {
                                echo '-- ';
                            }
                        }
                        echo $category['name'];
                        echo ' (' . $category['posts'] . ')';
                    ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div>
            <h3>Most Popular Blogs: </h3>
        </div>

        <div>
            <?php foreach ($popularBlogs as $blog): ?>
                <div>
                    <?php
                        echo $blog->getTitle();
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="subscribe form-group">Subscribe to our newsletter
            <form method="post" action="/blog/subscribe" id="subscribe">
                <label>Enter your email:</label>
                <input type="email" name="email" id="email"/>
                <button type="submit" id="submit" class="btn btn-info">Subscribe</button>
            </form>
        </div>
    </div>

</body>
</html>