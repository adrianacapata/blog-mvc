<?php

namespace Blog\Model\Repository;

use Blog\DependencyInjection\Container;
use Blog\Model\Entity\BlogEntity;

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
     * calculates the most popular blogs
     * formula: 65*views/100 + 15*(likes-dislike)/100 + 20*comments/100
     *
     * @return BlogEntity[]
     */
    public static function getPopularity(): array
    {
        $conn = Container::getDbConnection();

        $stmt = $conn->prepare(
            'SELECT b.*, 
            ifnull(:viewPercentage*b.views/100, 0) + ifnull(:commentPercentage*COUNT(c.id)/100, 0) + ifnull(:appreciationPercentage*(b.like_count-b.dislike_count)/100, 0) as popularity
            
            FROM `blog` b
            left JOIN comment c on b.id = c.blog_id
            GROUP BY b.id
            ORDER BY popularity DESC'
        );
        $stmt->execute([
            'viewPercentage' => self::VIEW_PERCENTAGE,
            'commentPercentage' => self::COMMENT_PERCENTAGE,
            'appreciationPercentage' => self::APPRECIATION_PERCENTAGE
        ]);

        $blogData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($blogData as $blog) {
            $blogs[] = self::createBlogEntityFromData($blog);
        }

        return $blogs;
    }

    /**
     * gets an array with data from db and returns a blog entity object
     *
     * @param array $blogData
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
        $blogEntity->setLikesCount($blogData['like_count']);
        $blogEntity->setDislikeCount($blogData['dislike_count']);
        $blogEntity->setUpdatedAt($blogData['updated_at']);
        $blogEntity->setViews($blogData['views']);

        return $blogEntity;
    }

    /**
     * Will return a single Blog entity from db by $id
     * @param int $id
     * @return BlogEntity
     */
    public static function findOneById(int $id): BlogEntity
    {
        $conn = Container::getDbConnection();

        $stmt = $conn->prepare('SELECT * FROM `blog` WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $blogData = $stmt->fetch(\PDO::FETCH_ASSOC);

        return self::createBlogEntityFromData($blogData);
    }
}