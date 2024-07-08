<?php

namespace plugse\server\core\infra\database\mysql;

use PDO;
use plugse\server\core\helpers\File;
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
    public array $relations;
    protected array $indexUniques;
    // protected array $indexForeigns;
    protected string $entity;
    protected string $mapper;

    public function __construct()
    {
        $this->dbSettings = File::getProperty(SETTINGS_FILE, 'db');
        $this->setTableName();
        $this->setPrimaryKey();
        // $this->setQuerySelectMany();
        $this->connection = Connection::getInstance($this->dbSettings);
        $this->setEntity();
        // $this->setMapper();
        $this->setRelations();
        // $this->setIndexUniques();
        // $this->setIndexForeigns();
    }

    abstract protected function setTableName(): void;

    abstract protected function setEntity(): void;

    protected function setPrimaryKey()
    {
        $this->primaryKey = 'id';
    }

    protected function setRelations()
    {
        $this->relations = [];
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
        try {
            $create = new Create();
            $response = $create->setQuery($this->getTableName(), $entity)->run($this->connection, $subqueries);

            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(string $id, Entity $entity): Entity
    {
        try {
            $update = new Update($this->connection);
            $response = $update->setQuery($this->getTableName(), $entity, $id)->run();

            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findMany(string $whereClauses, array $values, string $fields = '*'): array
    {
        try {
            $fields = $fields === '*' ? [] : explode(', ', $fields);
            $read = new Read($this->connection);
            $stmt = $read
                ->setTablename($this->getTableName())
                ->setFields($fields)
                ->setWhereClauses($whereClauses)
                ->run($values);
            $response = $read->fetchMany($stmt, $this->entity);

            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findOne(string $whereClauses, array $values, string $fields = '*'): Entity
    {
        try {
            $fields = $fields === '*' ? [] : explode(', ', $fields);
            $read = new Read($this->connection);
            $stmt = $read
                ->setTablename($this->getTableName())
                ->setFields($fields)
                ->setWhereClauses($whereClauses)
                ->run($values);
            $response = $read->fetchOne($stmt, $this->entity);

            if ($response) {
                return $response;
            }

            $emptyEntity = new $this->entity;

            return $emptyEntity;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function count(string $whereClauses, array $values = [], string $field = 'id'): int
    {
        try {
            $read = new Read($this->connection);
            $stmt = $read
                ->setTablename($this->getTableName())
                ->setCountField($field)
                ->setWhereClauses($whereClauses)
                ->run($values);

            $stmt = $read->run($values);

            return $read->fetchCount($stmt);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
