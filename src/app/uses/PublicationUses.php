<?php

namespace plugse\server\app\uses;

use Exception;
use plugse\server\app\entities\Copy;
use plugse\server\app\entities\Publication;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\app\uses\AbstractUses;
use plugse\server\infra\database\mysql\CopyModel;
use plugse\server\core\infra\database\mysql\Create;

class PublicationUses extends AbstractUses
{
    public function findManyByQuery(string $query): array
    {
        $values = [':query' => "%{$query}%"];
        $fields = ['title', 'originalTitle', 'subTitle', 'subjects', 'authors', 'themeCode'];
        $whereClauses = [];
        foreach ($fields as $field) {
            array_push($whereClauses, "{$field} LIKE :query");
        }

        return $this->model->findMany(
            implode(' OR ', $whereClauses), 
            $values, 
        );
    }

    public function create(Entity $entity, array $subqueries=[]): Entity
    {
        $this->checkPublication($entity);     

        $copies = $this->getCopies($entity);
        $entity->unset('copies');

        return parent::create($entity, $copies);
    }

    private function checkPublication(Publication $publication): void
    {
        $filedsToCheck = ['title', 'authorCode', 'themeCode', 'publisher', 'edition', 'volume'];
        $whereClauses = [];
        $values = [];

        foreach ($filedsToCheck as $field) {
            if($publication->has($field)) {
                $value = $publication->$field;
                if(strlen($value) > 0) {
                    array_push($whereClauses, "{$field}=:{$field}");
                    $values[":{$field}"] = $value;
                } else {
                    array_push($whereClauses, "{$field} IS NULL");
                }
            }
        }

        $count = $this->model->count( implode(' AND ', $whereClauses), $values );
        
        if($count > 0) {
            http_response_code(300);
            throw new Exception('Já temos essa publicação registrada');
        }
    }

    private function getCopies(Entity $entity): array
    {
        $copies = [];
        $countCopies = ($entity->has('copies') and ($entity->copies > 1)) ? intval($entity->copies) : 1;
        
        $year = date('Y', strtotime($entity->createdAt));
        $count = (new CopyModel)->count('createdAt LIKE :year', ['year' => "{$year}-%"]);

        for($i=0; $i<$countCopies; $i++) {            
            $copy = $this->getCopy(
                $year, 
                $entity->createdAt, $entity->createdBy, 
                $count + $i + 1
            );
            $query = (new Create)->setQuery('copy', $copy, 'publicationId')->getQuery();
            
            array_push($copies, $query);
        }
        
        return $copies;
    }
    
    private function getCopy(string $year, string $createdAt, int $createdBy, int $count): Copy
    {
        $copy = new Copy;
        $copy->registrationCode = "bib.{$year}." . $count;
        $copy->createdAt = $createdAt;
        $copy->createdBy = $createdBy;
        $copy->updatedBy = $createdBy;

        return $copy;
        
    }
}
