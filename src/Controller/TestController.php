<?php

namespace Blog\Controller;

class TestController
{
    public function testAction($id)
    {
       var_dump('the id is', $id); exit();
    }
}