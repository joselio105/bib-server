<?php

namespace plugse\server\infra\http\controllers;

use plugse\server\app\entities\User;
use plugse\server\app\uses\UserUses;
use plugse\server\app\mappers\UserMapper;
use plugse\server\app\schemas\UserSchema;
use plugse\server\infra\database\mysql\UserModel;
use plugse\server\core\infra\http\controllers\AbstractController;

class UsersController extends AbstractController
{
    protected function setUseCases()
    {
        $model = new UserModel();
        $this->uses = new UserUses($model);
    }

    protected function setEntityName()
    {
        $this->entityName = get_class(new User());
    }

    protected function setSchema()
    {
        $this->schema = new UserSchema();
    }

    protected function setMapper()
    {
        $this->mapper = new UserMapper($this->entity);
    }
}
