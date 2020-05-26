<?php

namespace Blog\Logger;

class FileLogger implements LoggerInterface
{
    /** @var string */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function log(string $message): void
    {
        file_put_contents($this->path, $message);
    }

}