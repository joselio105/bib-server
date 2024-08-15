<?php

namespace plugse\server\app\mappers;

use plugse\server\app\entities\User;
use plugse\server\core\app\mappers\Mapper;

class UserMapper extends Mapper
{
    public function __construct(User $user)
    {
        $this->attributes = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'isAdmin' => $user->isAdmin === '1',
            'isActive' => $user->isActive === '1',
        ];
    }
}
