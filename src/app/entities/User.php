<?php

namespace plugse\server\app\entities;

use plugse\server\core\app\entities\Entity;

class User extends Entity
{
    public $id;
    public $name;
    public $password;
    public $email;
    public $phone;
    public $isAdmin;
    public $isActive;
}
