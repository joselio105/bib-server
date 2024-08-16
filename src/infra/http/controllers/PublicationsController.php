<?php

namespace plugse\server\infra\http\controllers;

use plugse\server\infra\traits\CutterCode;
use plugse\server\app\entities\Publication;
use plugse\server\app\uses\PublicationUses;
use plugse\server\app\mappers\PublicationMapper;
use plugse\server\infra\database\mysql\PublicationsModel;
use plugse\server\core\infra\http\controllers\AbstractController;

class PublicationsController extends AbstractController
{
    use CutterCode;

    protected function setUseCases()
    {
        $model = new PublicationsModel();
        $this->entityName = $model->getEntity();
        $this->uses = new PublicationUses($model);
    }

    protected function setEntityName()
    {
        $this->entityName = get_class(new Publication());
    }

    protected function setEntity(array $body): void
    {
        parent::setEntity($body);

        $this->entity->authorCode = $this->getCutterCode($this->entity);
        $this->entity->createdAt = $this->getNow();
        $this->entity->createdBy = $this->getAuthUserId();
        $this->entity->updatedBy = $this->getAuthUserId();
    }

    protected function setEntityStored(int $id, array $body = []): void
    {
        parent::setEntityStored($id, $body);

        $this->entity->authorCode = $this->getCutterCode($this->entity);
        $this->entity->updatedBy = $this->getAuthUserId();
    }

    protected function getMapper(): array
    {
        $mapper = new PublicationMapper($this->entity);

        if ($this->entity->has('copies')) {
            $mapper->setCopies($this->entity->copies);
        }

        return $mapper->__serialize();
    }
}
