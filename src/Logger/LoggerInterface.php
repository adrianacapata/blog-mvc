<?php

namespace Blog\Logger;

interface LoggerInterface
{
    public function log(string $message);
}