<?php

namespace plugse\server\core\infra\http\controllers;

use plugse\server\core\app\uses\AbstractUses;
use plugse\server\core\infra\http\Request;
use plugse\server\core\infra\http\Response;

abstract class AbstractController
{
    protected AbstractUses $uses;

    public function __construct()
    {
        $this->setUseCases();
    }

    abstract protected function setUseCases();

    public function index(Request $request): Response
    {
        $response = $this->uses->findManyByQuery($request->params['query']);
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
