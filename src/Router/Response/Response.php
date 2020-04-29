<?php

namespace Blog\Router\Response;

use Blog\Router\Exception\InvalidTemplateException;

class Response implements ResponseInterface
{
    private const TEMPLATE_FILE_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR;

    private $filename;
    private $variables;

    public function __construct(string $filename, array $variables = [])
    {
        $this->filename = self::TEMPLATE_FILE_PATH . $filename;
        $this->variables = $variables;
    }

    /**
     * @throws InvalidTemplateException
     */
    public function render()
    {
        if (file_exists($this->filename)) {
            if (count($this->variables)) {
                extract($this->variables, EXTR_SKIP);
            }
            require_once $this->filename;
        } else {
            throw new InvalidTemplateException("`$this->filename` template file not found");
        }
        //header (status code)
    }
}