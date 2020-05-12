<?php

namespace Blog\Model\Repository;

use ArrayObject;
use Blog\DependencyInjection\Container;
use Blog\Model\Entity\NewsletterEntity;
use PDO;

class NewsletterRepository
{
    /**
     * @param array $data
     * @return NewsletterEntity
     */
    private static function createBlogSubscribeEntityFromData(array $data): NewsletterEntity
    {
        $blogSubscribe = new NewsletterEntity();
        $blogSubscribe->setEmail($data['email']);

        return $blogSubscribe;
    }

    /**
     * @param NewsletterEntity $blogSubscribe
     */
    public static function addSubscriber(NewsletterEntity $blogSubscribe): void
    {
        $conn = Container::getDbConnection();
        $email = $blogSubscribe->getEmail();

        $stmt = $conn->prepare('
            INSERT INTO `blog_subscribe` (email) VALUES (:email)
        ');
        $stmt->bindParam('email', $email);
        $stmt->execute();
    }

    /**
     * @return ArrayObject|NewsletterEntity[]
     */
    public static function getAllEmailAddresses(): ArrayObject
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->query('
            SELECT `email`
            FROM blog_subscribe
        ');
        $emailData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $blogSubscribe = new ArrayObject();
        foreach ($emailData as $email) {
            $blogSubscribe->append(self::createBlogSubscribeEntityFromData($email));
        }

        return $blogSubscribe;
    }

    /**
     * @param NewsletterEntity $blogSubscribe
     */
    public static function removeSubscriber(NewsletterEntity $blogSubscribe): void
    {
        $conn = Container::getDbConnection();
        $email = $blogSubscribe->getEmail();

        $stmt = $conn->prepare('
            DELETE FROM `blog_subscribe`
            WHERE `email` = :email
        ');
        $stmt->bindParam('email', $email);
        $stmt->execute();
    }

    /**
     * @param string $email
     * @return NewsletterEntity|null
     */
    public static function findOneByEmail(string $email): ?NewsletterEntity
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->prepare('
            SELECT * FROM `blog_subscribe` WHERE `email` = :email 
        ');

        $stmt->bindParam('email', $email);
        $stmt->execute();
        $blogSubscribeData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $blogSubscribeData ? self::createBlogSubscribeEntityFromData($blogSubscribeData) : null;
    }
}