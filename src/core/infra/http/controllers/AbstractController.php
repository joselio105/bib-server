<?php

namespace plugse\server\core\infra\http\controllers;

use Exception;
use plugse\server\core\app\mappers\Mapper;
use plugse\server\core\infra\http\Request;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\http\Response;
use plugse\server\core\app\uses\AbstractUses;
use plugse\server\core\app\validation\ValidationSchema;
use plugse\server\core\app\validation\validations\IsRequired;

// TODO: User - hasMany Loans
// TODO: Campos únicos...

abstract class AbstractController
{
    protected AbstractUses $uses;
    protected Entity $entity;
    protected string $entityName;
    protected ValidationSchema $schema;
    protected Mapper $mapper;

    public function __construct()
    {
        $this->setUseCases();
    }

    abstract protected function setUseCases();

    public function index(Request $request): Response
    {
        IsRequired::make($request->params, 'query')->validate();

        $found = $this->uses->findManyByQuery($request->params['query']);

        $response = [];
        foreach ($found as $entity) {
            $this->entity = $entity;
            $mapper = $this->getMapper();
            array_push($response, $mapper);
        }

        return new Response($response);
    }

    public function show(Request $request): Response
    {
        IsRequired::make($request->params, 'id')->validate();

        $this->entity = $this->uses->findOneById($request->params['id']);
        $response = $this->getMapper();

        return new Response($response);
    }

    public function create(Request $request): Response
    {
        $this->setEntity($request->body);
        $this->validate($this->entity->getAttributes());

        $response = $this->uses->create($this->entity);
        $this->entity = $response;

        return new Response(
            $this->getMapper($response),
            201
        );
    }

    public function update(Request $request): Response
    {
        IsRequired::make($request->params, 'id')->validate();

        $this->setEntityStored($request->params['id'], $request->body);
        $this->validate($this->entity->getAttributes());

        $response = $this->uses->update($request->params['id'], $this->entity);
        $this->entity = $response;

        return new Response(
            $this->getMapper($response)
        );
    }

    public function delete(Request $request): Response
    {
        IsRequired::make($request->params, 'id')->validate();
        http_response_code(404);

        throw new Exception('Função não implementada');
    }

    protected function getAuthUserId()
    {
        return 1;
    }

    protected function getNow()
    {
        return date('Y-m-d H:i:s');
    }

    protected function setTimestamp()
    {
        $this->entity->createdAt = $this->getNow();
    }

    protected function setUser()
    {
        $this->entity->createdBy = $this->getAuthUserId();
        $this->entity->updatedBy = $this->getAuthUserId();
    }

    protected function validate(array $body): void
    {
        $validation = $this->entity->getValidation($body);
        foreach ($validation as $schemas) {
            foreach ($schemas as $schema) {
                $schema->validate();
            }
        }
    }

    protected function setEntity(array $body): void
    {
        $this->entity = new $this->entityName();
        foreach ($body as $key => $value) {
            $this->entity->$key = $value;
        }

        $this->setTimestamp();
        $this->setUser();
    }

    protected function setEntityStored(int $id, array $body = []): void
    {
        $this->entity = $this->uses->findOneById($id);

        foreach ($body as $key => $value) {
            $this->entity->$key = $value;
        }
    }

    protected function getMapper(): array
    {
        $mapperName = $this->entity->getMapper();
        $this->mapper = new $mapperName($this->entity);

        return $this->mapper->__serialize();
    }
}
