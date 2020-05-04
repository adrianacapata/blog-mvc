<?php

namespace Blog\Router\Response;

class JSONResponse implements ResponseInterface
{
    /**
     * @var array
     */
    private $data;

    private $httpStatusCode;

    public function __construct(array $data, int $httpStatusCode = Response::HTTP_STATUS_OK)
    {
        $this->data = $data;
        $this->httpStatusCode = $httpStatusCode;
    }

    public function render()
    {
        http_response_code($this->httpStatusCode);

        echo json_encode($this->data);
    }
}