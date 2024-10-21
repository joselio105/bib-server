<?php

namespace plugse\server\core\errors;

use Exception;

class EntityNotFoundError extends Exception
{
    public function __construct(string $entityName, string $id)
    {
        http_response_code(404);
        parent::__construct("Erro: Nenhum '{$entityName}' corresponde ao id '{$id}'");
    }
}
