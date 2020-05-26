<?php

namespace Blog\Model\Repository;

use Blog\DependencyInjection\Container;
use PDO;

class LogRepository
{
    /**
     * @param string $message
     */
    public static function addMessage(string $message): void
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->prepare('INSERT INTO `log` (`message`) VALUES :message');
        $stmt->bindValue('message', $message, PDO::PARAM_STR);
        $stmt->execute();
    }
}