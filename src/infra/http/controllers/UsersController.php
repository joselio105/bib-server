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
        if(key_exists('name', $body)) {
            $entity->name =  $body['name'];
        }
        if(key_exists('email', $body)) {
            $entity->email =  $body['email'];
        }
        if(key_exists('phone', $body)) {
            $entity->phone =  $body['phone'];
        }
        if(key_exists('password', $body)) {
            $entity->password =  $body['password'];
        }
        if(key_exists('isAdmin', $body)) {
            $entity->isAdmin =  $body['isAdmin'];
        }
        if(key_exists('isActive', $body)) {
            $entity->isActive =  $body['isActive'];
        }

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
