<?php

namespace plugse\server\core\infra\http\controllers;

use Exception;
use plugse\server\core\app\mappers\Mapper;
use plugse\server\core\infra\http\Request;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\http\Response;
use plugse\server\core\app\uses\AbstractUses;
use plugse\server\core\app\validation\Validations;

// TODO: Publication - Generate authorCode - Qual escolher no caso de multiplos códigos?
// TODO: Publication - Create copies
// TODO: Publication - hasMany copies
// TODO: Copy - Validation - Generate registrationCode - belongsTo User - belongsTo Publication - hasMany Loans
// TODO: Loan - Validation
// TODO: User - hasMany Loans
// TODO: Campos únicos...

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
        Validations::isRequired($request->params, 'query');
        
        $found = $this->uses->findManyByQuery($request->params['query']);
        $response = [];
        foreach ($found as $entity){
            $mapper = $this->getMapper($entity);
            array_push($response, $mapper);
        }

        return new Response($response);
    }

    public function show(Request $request): Response
    {
        Validations::isRequired($request->params, 'id');

        $entity = $this->uses->findOneById($request->params['id']);
        $response = $this->getMapper($entity);
        
        return new Response($response);
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
        Validations::isRequired($request->params, 'id');

        $entity = $this->getEntity($request->body);
        $response = $this->uses->update($request->params['id'], $entity);
        return new Response(
            $this->getMapper($response)
        );
    }

    public function delete(Request $request): Response
    {
        http_response_code(404);
        throw new Exception('Função não implementada');
    }
}
