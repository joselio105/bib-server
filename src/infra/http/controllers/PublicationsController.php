<?php

namespace plugse\server\infra\http\controllers;

use plugse\server\infra\traits\CutterCode;
use plugse\server\app\entities\Publication;
use plugse\server\app\uses\PublicationUses;
use plugse\server\core\app\entities\Entity;
use plugse\server\app\mappers\PublicationMapper;
use plugse\server\infra\database\mysql\PublicationsModel;
use plugse\server\core\infra\http\controllers\AbstractController;

class PublicationsController extends AbstractController
{
    use CutterCode;

    protected function setUseCases()
    {
        $model = new PublicationsModel;
        $this->uses = new PublicationUses($model);
    }

    protected function getEntity(array $body, bool $isUpdate=false): Entity
    {
        
        $validations = require 'src/app/validations/PublicationValidation.php';
        $entity = new Publication($validations);

        foreach ($body as $key => $value) {
            $entity->$key = $value;
        }

        $entity->authorCode = $this->getCutterCode($entity);
        if(!$isUpdate){
            $entity->createdAt = $this->getNow();
            $entity->createdBy = $this->getAuthUserId();
        }
        $entity->updatedBy = $this->getAuthUserId();

        return $entity;
    }

    protected function getMapper(Entity $entity): array
    {
        $mapper = new PublicationMapper($entity);

        if($entity->has('copies')) {
            $mapper->setCopies($entity->copies);
        }

        return $mapper->__serialize();
    }
}
