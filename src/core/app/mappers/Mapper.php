<?php

namespace plugse\server\core\app\mappers;

use plugse\server\core\app\entities\Entity;
use plugse\server\core\errors\AttributeClassNotFoundError;

abstract class Mapper
{
    private array $attributes;

    public function __construct(Entity $entity)
    {
        $this->attributes = $entity->getAttributes();
    }

    public function __serialize(): array
    {
        return $this->attributes;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __get($name)
    {
        if(key_exists($name, $this->attributes)){
            return $this->attributes[$name];
        }
        
        throw new AttributeClassNotFoundError($name, self::class, $this::class);
    }
}
