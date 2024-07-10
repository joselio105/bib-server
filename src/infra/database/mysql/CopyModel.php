<?php

namespace plugse\server\infra\database\mysql;

use plugse\server\app\entities\Copy;
use plugse\server\app\entities\User;
use plugse\server\app\entities\Publication;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\mysql\Read;
use plugse\server\core\infra\database\mysql\InnerJoin;
use plugse\server\core\infra\database\mysql\ModelMysql;
use plugse\server\core\infra\database\relations\HasMany;
use plugse\server\core\infra\database\relations\RelationHasMany;
use plugse\server\core\infra\database\relations\RelationsBelongsTo;

class CopyModel extends ModelMysql
{
    protected function setEntity(): void
    {
        $this->entity = Copy::class;
    }

    protected function setTableName(): void
    {
        $this->tableName = 'copy';
    }

    protected function setRelations()
    {
        $this->relations = [
            'loans' => new HasMany('copyId', new LoanModel),
        ];
    }


    protected function buildQueryRead(string $whereClauses, array $values, string $fields = '*'): Read
    {
        $publicationsModel = new PublicationsModel;
            $publicationTable = $publicationsModel->getTableName();
            $userModel = new UserModel;

        $read = parent::buildQueryRead($whereClauses, $values);
        $read->setFields([
            'copy.id' => 'id',
            'copy.registrationCode' => 'registrationCode',
            'copy.publicationId' => 'publication',
            'copy.createdAt' => 'createdAt',
            'copy.createdBy' => 'createdBy',
            'copy.updatedAt' => 'updatedAt',
            'copy.updatedBy' => 'updatedBy',
        ])
        ->setInnerJoin(new InnerJoin(
            $publicationsModel,
            $this->getTableName() . '.publicationId',
            [
                "{$publicationTable}.id" => 'pub_id',
                "{$publicationTable}.title" => 'pub_title',
                "{$publicationTable}.originalTitle" => 'pub_originalTitle',
                "{$publicationTable}.subTitle" => 'pub_subTitle',
                "{$publicationTable}.subjects" => 'pub_subjects',
                "{$publicationTable}.authors" => 'pub_authors',
                "{$publicationTable}.themeCode" => 'pub_themeCode',
            ]
        ))
        ->setInnerJoin(new InnerJoin(
            $userModel,
            $this->getTableName() . '.createdBy',
            [
                'CREATOR.id' => 'creator_id',
                'CREATOR.name' => 'creator_name',
                'CREATOR.email' => 'creator_email',
            ],
            'CREATOR'
        ))
        ->setInnerJoin(new InnerJoin(
            $userModel,
            $this->getTableName() . '.updatedBy',
            [
                'UPDATOR.id' => 'updator_id',
                'UPDATOR.name' => 'updator_name',
                'UPDATOR.email' => 'updator_email',
            ],
            'UPDATOR'
        ));

        return $read;
    }

    public function findOne(string $whereClauses, array $values, string $fields = '*'): Entity
    {
        $response = parent::findOne($whereClauses, $values);
        $response = (new RelationHasMany($this))->hasManyOnEntity('loans', $response);
        $response = (new RelationsBelongsTo($response, 'pub_', new Publication))->get('publication');
        $response = (new RelationsBelongsTo($response, 'creator_', new User))->get('createdBy');
        $response = (new RelationsBelongsTo($response, 'updator_', new User))->get('updatedBy');

        return $response;
    }

    public function findMany(string $whereClauses, array $values, string $fields = '*'): array
    {
        $responses = [];

        foreach(parent::findMany($whereClauses, $values) as $entity){            
            $entity = (new RelationsBelongsTo($entity, 'pub_', new Publication))->get('publication');
            $entity = (new RelationsBelongsTo($entity, 'creator_', new User))->get('createdBy');
            $entity = (new RelationsBelongsTo($entity, 'updator_', new User))->get('updatedBy');

            array_push($responses, $entity);
        }

        return $responses;
    }
}
