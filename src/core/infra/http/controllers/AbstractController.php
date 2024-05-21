<?php

namespace plugse\server\core\infra\http\controllers;

use plugse\server\core\infra\http\Request;
use plugse\server\core\infra\http\Response;

class AbstractController
{
    public function index(Request $request): Response
    {
        return new Response(['response'=>$request->uri]);
    }

    public function show(Request $request): Response
    {
        return new Response(['response'=>$request->uri]);
    }

    public function create(Request $request): Response
    {
        return new Response(['response'=>$request->uri]);
    }

    public function update(Request $request): Response
    {
        return new Response(['response'=>$request->uri]);
    }

    public function delete(Request $request): Response
    {
        return new Response(['response'=>$request->uri]);
    }
}
