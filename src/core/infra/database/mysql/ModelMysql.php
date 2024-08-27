<?php

namespace plugse\server\core\infra\database\mysql;

use PDO;
use plugse\server\core\helpers\File;
use plugse\server\core\helpers\Crypto;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\Model;
use plugse\server\core\errors\ArrayKeyNotFoundError;
use plugse\server\core\infra\database\relations\HasMany;

abstract class ModelMysql implements Model
{
    private array $dbSettings;
    private PDO $connection;
    protected string $tableName;
    protected string $primaryKey;
    protected array $fields;
    public array $relations;
    protected array $indexUniques;
    protected string $entity;
    protected string $mapper;
    protected array $hashes;

    public function __construct()
    {
        $this->dbSettings = File::getProperty(SETTINGS_FILE, 'db');
        $this->setTableName();
        $this->setPrimaryKey();
        $this->setFields();
        $this->connection = Connection::getInstance($this->dbSettings);
        $this->setEntity();
        $this->setHashes();
        $this->setRelations();
    }

    abstract protected function setTableName(): void;

    abstract protected function setEntity(): void;

    protected function setHashes()
    {
        $this->hashes = [];
    }

    protected function setPrimaryKey()
    {
        $this->primaryKey = 'id';
    }

    protected function setFields()
    {
        $this->fields = [];
    }

    protected function setRelations()
    {
        $this->relations = [];
    }

    protected function formatEntity(Entity $entity): Entity
    {
        return $entity;
    }

    public function getTableName(): string
    {
        $table_prefix = $this->dbSettings['prefix'];
        ;

        return "{$table_prefix}{$this->tableName}";
    }

    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function getRelations(string $type): array
    {
        return array_filter($this->relations, function ($relation) use ($type) {
            return get_class($relation) === $type;
        });
    }

    public function getRelationHasMany(string $field): HasMany
    {
        $relations = $this->getRelations(HasMany::class);

        if (key_exists($field, $relations)) {
            return $relations[$field];
        }

        throw new ArrayKeyNotFoundError($field, 'Model::relations');
    }

    public function clearTable()
    {
        $this->connection->query("TRUNCATE {$this->getTableName()}");
    }

    public function create(Entity $entity, array $subqueries = []): Entity
    {
        $entity = $this->hash($entity);
        array_push($subqueries, $this->buildQueryRead('', [])->getQuery() . " {$this->getTableName()}.");

        try {
            $create = new Create();
            $response = $create->setQuery(
                $this->getTableName(),
                $entity
            )->run($this->connection, $subqueries);

            return $this->formatEntity($response);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(string $id, Entity $entity): Entity
    {
        $entity = $this->hash($entity);
        $query = "{$this->buildQueryRead('', [])->getQuery()} {$this->getTableName()}.";

        try {
            $update = new Update($this->connection);
            $response = $update->setQuery(
                $this->getTableName(),
                $this->getValues($entity),
                $id
            )->run($query, $this->entity);

            return $this->formatEntity($response);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findMany(string $whereClauses, array $values, string $fields = '*'): array
    {
        try {
            $read = $this->buildQueryRead($whereClauses, $values, $fields);
            $stmt = $read->run($values);
            $response = [];
            foreach ($read->fetchMany($stmt, $this->entity) as $entity) {
                array_push($response, $this->formatEntity($entity));
            }

            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findOne(string $whereClauses, array $values, string $fields = '*'): Entity
    {
        try {
            $read = $this->buildQueryRead($whereClauses, $values, $fields);
            $stmt = $read->run($values);
            $response = $read->fetchOne($stmt, $this->entity);

            if ($response) {
                return $response;
            }

            $emptyEntity = new $this->entity();

            return $emptyEntity;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function count(string $whereClauses, array $values = [], string $field = 'id'): int
    {
        try {
            $read = $this->buildQueryRead($whereClauses, $values);
            $read->setCountField($field);
            $stmt = $read->run($values);

            return $read->fetchCount($stmt);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function buildQueryRead(string $whereClauses, array $values = []): Read
    {
        $read = (new Read($this->connection))
            ->setTablename($this->getTableName())
            ->setFields($this->fields)
            ->setWhereClauses($whereClauses);

        return $read;
    }

    private function getValues(Entity $entity): array
    {
        $response = [];

        foreach (array_values($this->fields) as $field) {
            $response[$field] = $entity->$field;
        }

        return $response;
    }

    private function hash(Entity $entity)
    {
        foreach ($this->hashes as $field) {
            $entity->$field = Crypto::hash($entity->$field);
        }

        return $entity;
    }
}
