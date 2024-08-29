<?php

namespace plugse\server\core\infra\database\mysql;

use Exception;
use PDO;
use plugse\server\core\helpers\File;
use plugse\server\core\helpers\Crypto;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\infra\database\Model;

abstract class ModelMysql implements Model
{
    private array $dbSettings;
    private PDO $connection;
    protected string $tableName;
    protected string $primaryKey;
    protected array $fields;
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

    protected function formatEntity(Entity $entity): Entity
    {
        return $entity;
    }

    public function getTableName(): string
    {
        $table_prefix = $this->dbSettings['prefix'];

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

    public function findOne(string $whereClauses = '1', array $values = [], array $fields = []): Entity
    {
        $read = $this->buildQueryRead($whereClauses, $values, $fields);
        $stmt = $read->run($values);

        $response = $read->fetchOne($stmt, $this->entity);

        if (!$response) {
            throw new Exception($stmt->errorInfo()[2]);
        }

        return $this->formatFindOne($response);
    }

    public function findMany(string $whereClauses = '1', array $values = [], array $fields = []): array
    {
        $read = $this->buildQueryRead($whereClauses, $values, $fields);
        $stmt = $read->run($values);

        $response = $read->fetchMany($stmt, $this->entity);

        return $this->formatAllEntities($response);
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

    protected function buildQueryRead(string $whereClauses, array $values = [], array $fields = []): Read
    {
        $read = new Read($this->connection);
        $read
            ->setTablename($this->getTableName())
            ->setFields(empty($fields) ? $this->fields : $fields)
            ->setWhereClauses($whereClauses);

        return $read;
    }

    protected function formatFindOne(Entity $entity): Entity
    {
        return $entity;
    }

    protected function formatFindMany(Entity $entity): Entity
    {
        return $entity;
    }

    private function formatAllEntities(array $entities): array
    {
        $response = [];

        foreach ($entities as $entity) {
            array_push($response, $this->formatFindMany($entity));
        }

        return $response;
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
