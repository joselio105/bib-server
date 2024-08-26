<?php

namespace plugse\server\infra\http\controllers;

use DateInterval;
use DateTime;
use plugse\server\app\mappers\LoanMapper;
use plugse\server\app\uses\LoanUses;
use plugse\server\core\infra\http\controllers\AbstractController;
use plugse\server\infra\database\mysql\LoanModel;

class LoanController extends AbstractController
{
    public const RETURN_TIME = 28;

    protected function setUseCases()
    {
        $model = new LoanModel();
        $this->entityName = $model->getEntity();
        $this->uses = new LoanUses($model);
    }

    protected function setEntity(array $body): void
    {
        parent::setEntity($body);

        $this->getReturDate();
    }

    private function getReturDate()
    {
        $date = new DateTime($this->entity->loannedAt);
        $returnTime = new DateInterval('P' . self::RETURN_TIME . 'D');
        $date->add($returnTime);
        $this->entity->returnAt = $date->format('Y-m-d H:i:s');
    }

    protected function getMapper(): array
    {
        $mapper = new LoanMapper($this->entity);
        $mapper->setCopy($this->entity->copy, $this->entity->publication);
        $mapper->setUser($this->entity->user);
        $mapper->setCreator($this->entity->createdBy);
        $mapper->setUpdator($this->entity->updatedBy);

        return $mapper->__serialize();
    }
}
