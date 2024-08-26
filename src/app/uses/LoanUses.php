<?php

namespace plugse\server\app\uses;

use plugse\server\core\app\uses\AbstractUses;

class LoanUses extends AbstractUses
{
    public function findManyByQuery(string $query): array
    {
        return [];
    }
}
