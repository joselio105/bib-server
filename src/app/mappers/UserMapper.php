<?php

namespace plugse\server\app\mappers;

use plugse\server\app\entities\User;
use plugse\server\core\app\mappers\Mapper;

class UserMapper extends Mapper
{
    public function __construct(User $user)
    {
        parent::__construct($user);
        if (key_exists('isAdmin', $this->attributes)) {
            $this->attributes['isAdmin'] = $this->attributes['isAdmin'] === '1';
        }
        if (key_exists('isActive', $this->attributes)) {
            $this->attributes['isActive'] = $this->attributes['isActive'] === '1';
        }
    }
}
