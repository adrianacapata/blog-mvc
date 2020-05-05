<?php
/**
 * @var BlogEntity $posts
 */

use Blog\Model\Entity\BlogEntity;

?>

<div>Most popular posts by category: </div>

<?php if (!empty($posts)): ?>
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
<?php else: ?>
    <div>there are no posts in this category</div>
<?php endif; ?>

