<?php

namespace plugse\server\core\helpers;

use plugse\server\core\errors\FileNotFoundError;
use plugse\server\core\errors\PropertyNotFoundError;

class File
{
    public static function getFileData(string $filename)
    {
        if(!file_exists($filename)){
            throw new FileNotFoundError($filename);
        }

        return require($filename);
    }

    public static function getProperty(string $filename, string $propertyName): string|array
    {
        $settings = self::getFileData($filename);

        if (!key_exists('db', $settings)) {
            throw new PropertyNotFoundError($filename);
        }

        return $settings[$propertyName];
    }

    public static function saveFileData(string $filename, array $content)
    {
        $contentSave = file_exists($filename) ? self::getFileData($filename):[];

        foreach($content as $key=>$value){
            $contentSave[$key] = $value;
        }
        var_dump ($contentSave);
        file_put_contents($filename, self::arrayToPhpFile($contentSave));        
    }

    private static function arrayToPhpFile(array $content): string
    {
        $text = "<?php \n return [";


        foreach($content as $key=>$value){
            if(is_string($key)){
                $text .= "\n\t'{$key}'=>'{$value}',";
            }
        }

        return $text."\n];";   
    }

    public static function readJsonFile(string $filename): array
    {
        $content = file_get_contents($filename);
        if($content){
            return json_decode($content, JSON_OBJECT_AS_ARRAY);
        }

        return [];
    }
}
