<?php

namespace Blog\Router\Response;

class JSONResponse implements ResponseInterface
{

    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function render()
    {
        echo json_encode($this->data);
    }
}