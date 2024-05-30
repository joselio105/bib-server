<?php

namespace plugse\server\infra\http\controllers;

use plugse\server\app\entities\User;
use plugse\server\app\mappers\UserMapper;
use plugse\server\app\uses\UserUses;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\app\mappers\Mapper;
use plugse\server\core\infra\http\controllers\AbstractController;
use plugse\server\infra\database\mysql\UserModel;

class UsersController extends AbstractController
{
    protected function setUseCases()
    {
        $model = new UserModel;
        $this->uses = new UserUses($model);
    }

    protected function getEntity(array $body): Entity
    {
        $validations = require 'src/app/validations/UserValidation.php';
        $entity = new User($validations);
        $entity->name = key_exists('name', $body) ? $body['name'] : null;
        $entity->email = key_exists('email', $body) ? $body['email'] : null;
        $entity->phone = key_exists('phone', $body) ? $body['phone'] : null;
        $entity->isAdmin = key_exists('isAdmin', $body) ? $body['isAdmin'] : false;
        $entity->isActive = key_exists('isActive', $body) ? $body['isActive'] : true;

        return $entity;
    }

    protected function getMapper(Entity $entity): Mapper
    {
        return new UserMapper(
            $entity->id,
            $entity->name,
            $entity->email,
            $entity->phone,
            $entity->isAdmin===1,
            $entity->isActive===1,
        );
    }
}
