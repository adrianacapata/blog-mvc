<?php

namespace Blog\Model\Repository;

use Blog\DependencyInjection\Container;

class LogRepository
{
    public function addMessage(string $message): void
    {
        $conn = Container::getDbConnection();
        $stmt = $conn->prepare('INSERT INTO `log` (message) VALUES (:message)');
        $stmt->execute(['message' => $message]);
    }
}