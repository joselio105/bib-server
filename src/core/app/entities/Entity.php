<?php

namespace plugse\server\core\app\entities;

abstract class Entity
{
    public function __construct(private array $validations=[])
    {}

    public function getValidation(): array
    {
        return $this->validations;
    }

    public function getAttributes(): array
    {
        $abstract = get_class_vars(self::class);
        $all = get_class_vars(static::class);

        $response = [];
        foreach(array_keys(array_diff_key($all, $abstract)) as $attr){
            if(!is_null($this->$attr)){
                $response[$attr] = $this->$attr;
            }
        }
        
        return $response;
    }
}
