<?php

namespace Blog\Logger;

use Blog\DependencyInjection\Container;
use Blog\Model\Repository\LogRepository;

class DbLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        /** @var LogRepository $logRepository */
        $logRepository = Container::getRepository(LogRepository::class);
        $logRepository->addMessage($message);
    }
}