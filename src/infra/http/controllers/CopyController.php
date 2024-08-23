<?php

namespace plugse\server\infra\http\controllers;

use plugse\server\app\uses\CopyUses;
use plugse\server\app\mappers\CopyMapper;
use plugse\server\infra\database\mysql\CopyModel;
use plugse\server\core\infra\http\controllers\AbstractController;

class CopyController extends AbstractController
{
    protected function setUseCases()
    {
        $model = new CopyModel();
        $this->entityName = $model->getEntity();
        $this->uses = new CopyUses($model);
    }

    protected function setEntity(array $body): void
    {
        parent::setEntity($body);

        $this->entity->createdAt = $this->getNow();
        $this->entity->createdBy = $this->getAuthUserId();
        $this->entity->updatedBy = $this->getAuthUserId();
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
