<?php

namespace plugse\server\infra\http\controllers;

use plugse\server\app\entities\Copy;
use plugse\server\app\uses\CopyUses;
use plugse\server\app\mappers\CopyMapper;
use plugse\server\core\app\entities\Entity;
use plugse\server\infra\database\mysql\CopyModel;
use plugse\server\core\app\validation\Validations;
use plugse\server\core\infra\http\controllers\AbstractController;

class CopyController extends AbstractController
{
    protected function setUseCases()
    {
        $model = new CopyModel;
        $this->uses = new CopyUses($model);
    }

    protected function getEntity(array $body, bool $isUpdate = false): Entity
    {
        $entity = new Copy(Validations::getValidations('copy'));

        if (!$isUpdate) {
            $entity->createdAt = $this->getNow();
            $entity->createdBy = $this->getAuthUserId();
        }
        $entity->updatedBy = $this->getAuthUserId();

        $year = date('Y', strtotime($entity->createdAt));
        $count = (new CopyModel)->count('createdAt LIKE :year', ['year' => "{$year}-%"]);
        $entity->registrationCode = "bib.{$year}." . $count + 1;
        if (key_exists('publicationId', $body)) {
            $entity->publicationId = $body['publicationId'];
        }

        return $entity;
    }

    protected function getMapper(Entity $entity): array
    {
        $mapper = new CopyMapper($entity);
        if ($entity->has('publication')) {
            $mapper->setPublication($entity->publication);
            $mapper->setCreator($entity->createdBy);
            $mapper->setUpdator($entity->updatedBy);
        }

        return $mapper->__serialize();
    }
}
