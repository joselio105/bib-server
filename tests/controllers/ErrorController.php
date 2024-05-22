<?php

namespace plugse\test\controllers;

use plugse\server\core\infra\http\Request;
use plugse\server\core\infra\http\Response;

class ErrorControllers
{
    public function index(Request $req): Response
    {
        return new Response($req->params);
    }
}
