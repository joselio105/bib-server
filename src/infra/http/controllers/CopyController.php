<?php

namespace plugse\server\infra\http\controllers;

use plugse\server\app\uses\CopyUses;
use plugse\server\app\mappers\CopyMapper;
use plugse\server\core\app\validation\validations\IsRequired;
use plugse\server\infra\database\mysql\CopyModel;
use plugse\server\core\infra\http\controllers\AbstractController;
use plugse\server\core\infra\http\Request;
use plugse\server\core\infra\http\Response;

class CopyController extends AbstractController
{
    public function findOneByCode(Request $request): Response
    {
        IsRequired::make($request->params, 'code');
        $code = substr($request->params['code'], 0, 3) . '.' . substr($request->params['code'], 3, 4) . '.' . substr($request->params['code'], 7);

        $this->entity = $this->uses->findOneByRegistrationCode($code);
        $response = $this->getMapper();

        return new Response($response);
    }
    protected function setUseCases()
    {
        $model = new CopyModel();
        $this->entityName = $model->getEntity();
        $this->uses = new CopyUses($model);
    }

    protected function getMapper(): array
    {
        $mapper = new CopyMapper($this->entity);

        if ($this->entity->has('publication')) {
            $mapper->setPublication($this->entity->publication);
        }

        if (is_object($this->entity->createdBy)) {
            $mapper->setCreator($this->entity->createdBy);
        }
        if (is_object($this->entity->updatedBy)) {
            $mapper->setUpdator($this->entity->updatedBy);
        }

        return $mapper->__serialize();
    }
}
