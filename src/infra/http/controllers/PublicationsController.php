<?php

namespace plugse\server\infra\http\controllers;

use plugse\server\app\uses\PublicationUses;
use plugse\server\infra\database\mysql\PublicationsModel;
use plugse\server\core\infra\http\controllers\AbstractController;

class PublicationsController extends AbstractController
{
    protected function setUseCases()
    {
        $model = new PublicationsModel;
        $this->uses = new PublicationUses($model);
    }
}
