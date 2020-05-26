<?php

namespace Blog\Logger;

class Logger implements LoggerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
   {
       $this->logger = $logger;
   }

    public function log(string $message)
    {
        // TODO: Implement log() method.
    }
}