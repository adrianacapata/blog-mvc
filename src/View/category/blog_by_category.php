<?php
/**
 * * @var \Blog\Model\Entity\BlogEntity[] $blogs
 */
?>

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
        <h1>Blogs by category</h1>
    </div>

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
                    <td><?php echo $blog->getTitle();?></td>
                    <td><?php echo $blog->getCreatedAt();?></td>
                    <td><?php echo $blog->getLikeCount();?></td>
                    <td><?php echo $blog->getDislikeCount();?></td>
                    <td><?php echo rand(1,10); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div>

    </div>
</body>
</html>
