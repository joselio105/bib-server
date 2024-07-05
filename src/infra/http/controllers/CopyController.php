<?php

namespace plugse\server\infra\http\controllers;

use plugse\server\app\entities\Copy;
use plugse\server\app\mappers\CopyMapper;
use plugse\server\app\uses\CopyUses;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\http\controllers\AbstractController;
use plugse\server\infra\database\mysql\CopyModel;

class CopyController extends AbstractController
{
    protected function setUseCases()
    {
        $model = new CopyModel;
        $this->uses = new CopyUses($model);
    }

    protected function getEntity(array $body, bool $isUpdate = false): Entity
    {
        $entity = new Copy();

        foreach ($body as $key=>$value) {
            $entity->$key = $value;
        }

        return $entity;
    }

    protected function getMapper(Entity $entity): array
    {
        $mapper = new CopyMapper($entity);
        if ($entity->has('publication')) {
            $mapper->setPublication($entity->publication);
        }

        return $mapper->__serialize();
    }
}
