<?php

namespace Blog\Logger;

use Blog\Model\Repository\LogRepository;

class DbLogger implements LoggerInterface
{

    public function log(string $message)
    {
        LogRepository::addMessage($message);
    }
}