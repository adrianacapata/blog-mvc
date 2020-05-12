<?php

namespace Blog\Model\Repository;

use ArrayObject;
use Blog\DependencyInjection\Container;
use Blog\Model\Entity\BlogEntity;
use PDO;

/**
 * Class PostRepository
 * @package Blog\Model\Repository
 */
class BlogRepository
{
    // percentage nr for views
    private const VIEW_PERCENTAGE = 65;
    //percentage nr for (like-dislike)
    private const APPRECIATION_PERCENTAGE = 15;
    //percentage nr for comment
    private const COMMENT_PERCENTAGE = 20;

    /**
     * gets an array with data from db and returns a post entity object
     *
     * @param array $blogData [
     *
     * ]
     * @return BlogEntity
     */
    private static function createBlogEntityFromData(array $blogData): BlogEntity
    {
        $blogEntity = new BlogEntity();
        $blogEntity->setId($blogData['id']);
        $blogEntity->setCategoryId($blogData['category_id']);
        $blogEntity->setAuthorName($blogData['author_name']);
        $blogEntity->setTitle($blogData['title']);
        $blogEntity->setContent($blogData['content']);
        $blogEntity->setCreatedAt($blogData['created_at']);
        $blogEntity->setStatus($blogData['status']);
        $blogEntity->setLikeCount($blogData['like_count']);
        $blogEntity->setDislikeCount($blogData['dislike_count']);
        $blogEntity->setUpdatedAt($blogData['updated_at']);
        $blogEntity->setViews($blogData['views']);
        $blogEntity->setCommentNr($blogData['comments_nr'] ?? null);

        return $blogEntity;
    }

    /**
     * Returns a single Blog entity from db by $id
     *
     * @param int $blogId
     * @return BlogEntity|null
     */
    public static function findOneById(int $blogId): ?BlogEntity
    {
        $conn = Container::getDbConnection();

        $stmt = $conn->prepare('
            SELECT b.*, COALESCE(COUNT(c.id), 0) comments_nr  
            FROM `blog` b
            LEFT JOIN comment c on b.id = c.blog_id
            WHERE blog_id = :blogId
            GROUP BY b.id
            ');
        $stmt->execute(['blogId' => $blogId]);
        $blogData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $blogData ? self::createBlogEntityFromData($blogData) : null;
    }

    /**
     * calculates the most popular blogs
     * formula: 65*views/100 + 15*(likes-dislike)/100 + 20*comments/100
     *
     * @return ArrayObject|BlogEntity[]
     */
    public static function getPopularity(): ArrayObject
    {
        $conn = Container::getDbConnection();

        $stmt = $conn->prepare(
            'SELECT b.*, 
            ifnull(:viewPercentage*b.views/100, 0) + ifnull(:commentPercentage*COUNT(c.id)/100, 0) + ifnull(:appreciationPercentage*(b.like_count-b.dislike_count)/100, 0) as popularity, COALESCE(COUNT(c.id), 0) comments_nr
            FROM `blog` b
            left JOIN comment c on b.id = c.blog_id
            WHERE b.status = :published
            GROUP BY b.id
            ORDER BY popularity DESC'
        );
        $stmt->execute([
            'viewPercentage' => self::VIEW_PERCENTAGE,
            'commentPercentage' => self::COMMENT_PERCENTAGE,
            'appreciationPercentage' => self::APPRECIATION_PERCENTAGE,
            'published' => BlogEntity::STATUS_PUBLISHED
        ]);

        $blogData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $blogs = new ArrayObject();
        foreach ($blogData as $blog) {
            $blogs->append(self::createBlogEntityFromData($blog));
        }

        return $blogs;
    }

    /**
     * Returns post entities filtered by category id
     *
     * @param int $categoryId
     * @param int $limit
     * @param int|null $offset
     * @return ArrayObject|BlogEntity[]
     */
    public static function findBlogsByCategoryId(int $categoryId, int $limit, int $offset = 0): ArrayObject
    {
        $conn = Container::getDbConnection();

        $stmt = $conn->prepare('
            SELECT b.*, COALESCE(COUNT(c.id), 0) comments_nr  
            FROM `blog` b
            LEFT JOIN comment c on b.id = c.blog_id
            WHERE category_id = :id AND b.status = :published
            GROUP BY b.id
            LIMIT :offset, :limit
        ');
        $stmt->bindParam('id', $categoryId);
        $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue('published', BlogEntity::STATUS_PUBLISHED);
        $stmt->bindParam('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $blogData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $blogs = new ArrayObject();
        foreach ($blogData as $blog) {
            $blogs->append(self::createBlogEntityFromData($blog));
        }

        return $blogs;
    }

    public static function countBlogsByCategoryId(int $categoryId): int
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->prepare('
            SELECT count(*)
            FROM `blog`
            WHERE category_id = :categoryId AND status = :published
          '
        );
        $stmt->execute([
            'categoryId' => $categoryId,
            'published' => BlogEntity::STATUS_PUBLISHED
        ]);

        return $stmt->fetchColumn();
    }

    /**
     * @param int $blogId
     */
    public static function incrementLikeCountByBlogId(int $blogId): void
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->prepare('
            UPDATE `blog` 
            SET `like_count` = `like_count` + 1 
            WHERE id = :blogId
        ');
        $stmt->execute(['blogId' => $blogId]);
    }

    public static function incrementDislikeCountByBlogId(int $blogId): void
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->prepare('
            UPDATE `blog` 
            SET `dislike_count` = `dislike_count` + 1 
            WHERE id = :blogId
        ');
        $stmt->execute(['blogId' => $blogId]);
    }

    /**
     * Archive blogs older than one year
     * Returns nr of archived blogs
     * @return int
     */
    public static function archiveBlogs(): ?int
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->prepare('
            UPDATE `blog`
            SET `status` = :archived
            WHERE updated_at < DATE_SUB(NOW(), INTERVAL 1 YEAR) 
              AND `status` != :archived
        ');
        $stmt->execute(['archived' => BlogEntity::STATUS_ARCHIVED]);

        return $stmt->rowCount();
    }

    /**
     * @return ArrayObject|BlogEntity[]
     */
    public static function getArchivedBlogs(): ArrayObject
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->query('
            SELECT * FROM `blog`
            WHERE `status` = :archived
        ');
        $stmt->bindValue('archived', BlogEntity::STATUS_ARCHIVED);
        $blogData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $blogs = new ArrayObject();
        foreach ($blogData as $blog) {
            $blogs->append(self::createBlogEntityFromData($blog));
        }

        return $blogs;
    }
}