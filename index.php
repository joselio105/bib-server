<?php

use plugse\server\core\infra\http\Bootstrap;
use plugse\server\core\app\validation\validations\MustBeInt;
use plugse\server\core\app\validation\validations\MustBeUrl;
use plugse\server\core\app\validation\validations\MustBeHour;
use plugse\server\core\app\validation\validations\IsRequired;
use plugse\server\core\app\validation\validations\MustBeBool;
use plugse\server\core\app\validation\validations\MustBeDate;
use plugse\server\core\app\validation\validations\MustBeEmail;
use plugse\server\core\app\validation\validations\MustBePhone;
use plugse\server\app\validations\validations\MusBeAuthorList;
use plugse\server\core\app\validation\validations\MustBeString;
use plugse\server\core\app\validation\validations\MustHaveLenght;
use plugse\server\core\app\validation\validations\MustBeDatetime;
use plugse\server\core\app\validation\validations\mustHaveNumbers;
use plugse\server\core\app\validation\validations\mustHaveSpecialChars;
use plugse\server\core\app\validation\validations\MustHaveLowerCaseChars;
use plugse\server\core\app\validation\validations\mustHaveUpperCaseChars;

require './vendor/autoload.php';

// use plugse\server\core\infra\http\Bootstrap;

// (new Bootstrap)->run();

$attributes = [
    'id' => 1,
    'isActive' => '0',
    'name' => '0',
    'phone' => '481234566770',
    'email' => 'jose@k.b',
    'age' => '0',
    'url' => 'https://localhost/bib-server/index.php?id=2#fim',
    'birthDay' => '2250-08-13',
    'createdAt' => '1900-01-01 23:59:59',
    'time' => '00:00:00',
    'altura' => '1234',
    'comprimento' => '12345',
    'largura' => '123',
    'profundidade' => '12345',
    'espessura' => '1234',
    'password' => 'aA1@',
    'authors' => 'José hélio; Júnior, Veríssimo',

];

$validations = [
    IsRequired::make($attributes, 'id'),
    MustBeInt::make($attributes, 'age'),
    MustBeBool::make($attributes, 'isActive'),
    MustBeString::make($attributes, 'name'),
    MustBePhone::make($attributes, 'phone'),
    MustBeEmail::make($attributes, 'email'),
    MustBeUrl::make($attributes, 'url'),
    MustBeDate::make($attributes, 'birthDay'),
    MustBeDatetime::make($attributes, 'createdAt'),
    MustBeHour::make($attributes, 'time'),
    MustHaveLenght::make($attributes, 'altura', 4),
    MustHaveLenght::make($attributes, 'comprimento', 4, MustHaveLenght::LENGTH_GREATHER),
    MustHaveLenght::make($attributes, 'largura', 4, MustHaveLenght::LENGTH_SMALLER),
    MustHaveLenght::make($attributes, 'profundidade', 4, MustHaveLenght::LENGTH_GREATHER_EQUALS),
    MustHaveLenght::make($attributes, 'espessura', 4, MustHaveLenght::LENGTH_SMALLER_EQUALS),
    MustHaveLowerCaseChars::make($attributes, 'password'),
    mustHaveUpperCaseChars::make($attributes, 'password'),
    mustHaveNumbers::make($attributes, 'password'),
    mustHaveSpecialChars::make($attributes, 'password'),
    MusBeAuthorList::make($attributes, 'authors'),
    // MustBeCutter::make($attributes, 'cutter'),
    // MustBeForeignKey::make($attributes, 'foreignKey'),
    // MustBeRegistration::make($attributes, 'registration'),

];

try {
    foreach ($validations as $validation) {
        $validation->validate();
    }

    echo 'FIM';
} catch (\Throwable $th) {
    echo 'ERRO: ' . $th->getMessage();
}
