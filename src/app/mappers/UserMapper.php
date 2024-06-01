<?php

namespace plugse\server\app\mappers;

use plugse\server\core\app\mappers\Mapper;

readonly class UserMapper implements Mapper
{
    public function __construct(
        public int $id, 
        public string $name, 
        public string $email, 
        public string $phone, 
        public bool $isAdmin, 
        public bool $isActive
    )
    {}
}
