<?php

namespace plugse\server\core\infra\http\controllers;

use plugse\server\app\mappers\UserMapper;
use plugse\server\core\infra\http\Request;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\app\mappers\Mapper;
use plugse\server\core\infra\http\Response;
use plugse\server\core\app\uses\AbstractUses;
use plugse\server\core\app\validation\Validations;

abstract class AbstractController
{
    protected AbstractUses $uses;

    public function __construct()
    {
        $this->setUseCases();
    }

    abstract protected function setUseCases();
    abstract protected function getEntity(array $body): Entity;
    abstract protected function getMapper(Entity $entity): Mapper;

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
        $entity = $this->getEntity($request->body);
        Validations::validate($entity);

        $response = $this->uses->create($entity);

        return new Response(
            $this->getMapper($response), 201
        );
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
