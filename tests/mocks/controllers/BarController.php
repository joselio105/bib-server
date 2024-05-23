<?php

namespace plugse\test\mocks\controllers;

use plugse\server\core\infra\http\Request;
use plugse\server\core\infra\http\Response;

class BarController
{
    public function index(Request $request): Response
    {
        return new Response($request->params);
    }
}
