<?php

namespace plugse\server\core\helpers;

use ReflectionClass;
use plugse\server\core\errors\FileNotFoundError;
use plugse\server\core\errors\ClassNotFoundError;
use plugse\server\core\errors\ActionNotFoundError;

class Classname
{
    public static function runClass(string $classname, array $params=[]): object
    {
        $filename = self::getFilename($classname);
        if(!file_exists($filename)){
            throw new FileNotFoundError($filename);
        }

        if(!class_exists($classname)){
            throw new ClassNotFoundError($classname);
        }

        return new $classname(...$params);
    }

    public static function runMethod(string $classname, string $method, array $classParams=[], array $methodParams=[])
    {
        $controller = Classname::runClass($classname, $classParams);
        if(!method_exists($controller, $method)){
            throw new ActionNotFoundError($method, $classname);
        }
        
        return $controller->$method(...$methodParams);
    }

    public static function getFilename(string $classname): string
    {
        $reflection = new ReflectionClass($classname);

        return $reflection->getFileName();
    }
}
