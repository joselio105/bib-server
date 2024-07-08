<?php

use plugse\server\core\app\validation\Validations;
use plugse\server\core\errors\FileNotFoundError;

test('validation file', function(){
    $entities = ['user', 'Publication', 'COPY', 'foo'];

    foreach ($entities as $entity) {
        $validations = Validations::getValidations($entity);

        expect($validations)->toBeArray();        
    }
});
