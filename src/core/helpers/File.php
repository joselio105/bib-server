<?php

namespace plugse\server\core\helpers;

use plugse\server\core\errors\FileNotFoundError;
use plugse\server\core\errors\ClassNotFoundError;

class File
{
    public static function getFileData(string $filename)
    {
        if(!file_exists($filename)){
            throw new FileNotFoundError($filename);
        }

        return require($filename);
    }

    public static function runClass(string $classname): object
    {
        $filename = self::getFilename($classname);
        if(!file_exists($filename)){
            throw new FileNotFoundError($filename);
        }

        if(!class_exists($classname)){
            throw new ClassNotFoundError($classname);
        }

        return new $classname;
    }

    public static function readJsonFile(string $filename): array
    {
        $content = file_get_contents($filename);
        if($content){
            return json_decode($content, JSON_OBJECT_AS_ARRAY);
        }

        return [];
    }

    private static function getFilename(string $classname): string
    {
        $composerJson = self::readJsonFile('composer.json');
        $autoload = $composerJson['autoload']['psr-4'];
        $namespace = array_keys($autoload)[0];
        $path = $autoload[$namespace];

        return './'.str_replace('\\', '/', str_replace($namespace, $path, $classname)).'.php';
    }
}
