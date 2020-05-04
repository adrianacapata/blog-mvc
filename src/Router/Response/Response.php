<?php

namespace Blog\Router\Response;

use Blog\Router\Exception\InvalidTemplateException;

class Response implements ResponseInterface
{
    public const HTTP_STATUS_OK = 200;
    public const HTTP_STATUS_BAD_REQUEST = 400;
    public const HTTP_STATUS_NOT_FOUND = 404;
    public const HTTP_STATUS_SERVER_ERROR = 500;

    private const TEMPLATE_FILE_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR;

    private $filename;
    private $variables;
    private $httpStatusCode;

    public function __construct(string $filename, array $variables = [], int $httpStatusCode = self::HTTP_STATUS_OK)
    {
        $this->filename = self::TEMPLATE_FILE_PATH . $filename;
        $this->variables = $variables;
        $this->httpStatusCode = $httpStatusCode;
    }

    /**
     * @throws InvalidTemplateException
     */
    public function render()
    {
        http_response_code($this->httpStatusCode);

        if (file_exists($this->filename)) {
            if (count($this->variables)) {
                extract($this->variables, EXTR_SKIP);
            }
            require_once $this->filename;
        } else {
            throw new InvalidTemplateException("`$this->filename` template file not found");
        }
    }

    /**
     * @param mixed $httpStatusCode
     */
    public function setHttpStatusCode($httpStatusCode): void
    {
        $this->httpStatusCode = $httpStatusCode;
    }

}