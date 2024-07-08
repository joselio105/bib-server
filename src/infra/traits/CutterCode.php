<?php

namespace plugse\server\infra\traits;

use Exception;
use plugse\server\core\helpers\File;
use plugse\server\app\entities\Publication;
use plugse\server\core\errors\FileNotFoundError;

trait CutterCode
{
    public function getCutterCode(Publication $publication): string
    {                
        if($publication->has('cutterCode')){
            return $publication->cutterCode;
        }

        $titleFirstChar = $this->getTitleFirstChar($publication);
        $sufix = $publication->has('authors')? $titleFirstChar : '';
        $code =  "{$this->getAuthorCode($publication)}{$sufix}";
        
        return $code;
    }
    
    private function getTitleFirstChar(Publication $publication): string
    {
        $language = $this->getLanguage($publication);
        $title = $this->getTitleAsArray($publication);
        
        return substr($title[0], 0, 1);
    }

    private function getAuthorCode(Publication $publication): string
    {
        $nameArray = $publication->has('authors') ? explode('; ', $publication->authors) : $this->getTitleAsArray($publication);
        $firstChar = strtoupper(substr($nameArray[0], 0, 1));

        return $firstChar . $this->findCode($nameArray[0]);
        
    }
    
    private function findCode(string $name): string
    {
        $response = array_filter($this->getCutterTable(), function($cutter) use($name) {
            $length = strlen($cutter['string']);
            return $cutter['string'] === substr($name, 0, $length);
        });

        $length = 0;
        return array_reduce($response, function($carry, $item) use ($length){
            $carry = strlen($item['string']);
            if($carry >= $length) {
                return $item['code'];
            }

            $length =  $carry;
        });
    }
    
    private function getTitleAsArray(Publication $publication): array
    {
        $language = $this->getLanguage($publication);
        $ignoredWords = $this->getLanguagesTable($language)['ignore'];
        $title = $language === $publication->publicationLanguage ? $publication->title : $publication->originalTitle;
        $title = explode(' ', strtolower($title));

        if(in_array($title[0], $ignoredWords)){
            array_shift($title);
        }

        return $title;
    }
    
    private function getLanguage(Publication $publication): string
    {
        return $publication->has('originalLanguage') ? $publication->originalLanguage : $publication->publicationLanguage;
    }

    private function getLanguagesTable(string $language): array
    {
        $table = File::readJsonFile('src/infra/database/file/languages.json');

        return array_filter($table, function ($lang) use ($language) {
            return $lang['code'] === $language;
        })[0];
    }

    private function getCutterTable(): array
    {
        $filename = 'src/infra/database/file/cutter.csv';

        if(!file_exists($filename)){
            throw new FileNotFoundError($filename);
        }
        
        $response = [];
        foreach(file($filename) as $content) {
            [$code, $string] = explode(';', $content);
            array_push($response, [
                'code' => $code, 
                'string' => trim($string, "\r\n")
            ]);
        }

        return $response;
    }
}
