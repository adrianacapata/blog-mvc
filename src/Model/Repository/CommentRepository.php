<?php

namespace Blog\Model\Repository;

use Blog\DependencyInjection\Container;
use Blog\Model\Entity\CommentEntity;
use PDO;

class CommentRepository
{
    /**
     * Returns a CommentEntity object from an array
     */
    private function createCommentEntityFromData(array $commentData): CommentEntity
    {
        $commentEntity = new CommentEntity();
        $commentEntity->setId($commentData['id']);
        $commentEntity->setCreatedAt($commentData['created_at']);
        $commentEntity->setAuthorName($commentData['author_name']);
        $commentEntity->setBlogId($commentData['blog_id']);
        $commentEntity->setContent($commentData['content']);
        $commentEntity->setStatus($commentData['status']);

        return $commentEntity;
    }

    public function getCommentsByBlogId(int $blogId): array
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->prepare('
            SELECT c.*
            FROM `comment` c 
            JOIN blog b on c.blog_id = b.id
            WHERE b.id = :blogId
            GROUP BY c.id 
            ORDER BY c.created_at DESC
        ');
        $stmt->execute(['blogId' => $blogId]);
        $commentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $comments = [];
        foreach ($commentsData as $commentData) {
            $comments[] = $this->createCommentEntityFromData($commentData);
        }
        return $comments;
    }

    public function addCommentToBlogId(CommentEntity $commentEntity): void
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->prepare('
            INSERT INTO `comment`
            (blog_id, author_name, content) 
            VALUES (:blogId, :authorName, :content) 
        ');
        $stmt->bindValue('blogId', $commentEntity->getBlogId(), PDO::PARAM_INT);
        $stmt->bindValue('authorName', $commentEntity->getAuthorName());
        $stmt->bindValue('content', $commentEntity->getContent());
        $stmt->execute();
    }

    /**
     * Returns the last inserted comment by Blog id
     */
    public function getLastAddedCommentByBlogId(int $blogId): ?CommentEntity
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->prepare('
            SELECT *
            FROM `comment`
            WHERE blog_id = :blogId
            ORDER BY created_at DESC 
            LIMIT 1
        ');
        $stmt->execute(['blogId' => $blogId]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);

        return $comment ? $this->createCommentEntityFromData($comment) : null;
    }
}