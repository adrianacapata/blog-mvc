<?php
/**
 * @var BlogEntity[] $posts
 * @var NewsletterEntity $email
 */

use Blog\Model\Entity\BlogEntity;
use Blog\Model\Entity\NewsletterEntity;

?>

<div>Most popular posts: </div>

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
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= $post->getTitle() ?></td>
                <td><?= $post->getCreatedAt() ?></td>
                <td><?= $post->getLikeCount() ?></td>
                <td><?= $post->getDislikeCount() ?></td>
                <td><?= $post->getCommentNr() ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<div>
    <a href="http://blog.local/subscribe/remove?email=<?php echo $email->getEmail()?>">Unsubscribe</a>
</div>

