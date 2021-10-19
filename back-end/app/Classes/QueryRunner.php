<?php

namespace App\Classes;

use App\Exceptions\QueryException;
use App\Facades\Connection;
use App\Facades\Database;
use App\Models\Query;
use PDO;

class QueryRunner
{
    public array $values = [];

    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    public function run(Query $query)
    {
        $connection = $this->createConnectionUsingQuery($query);

        info(json_encode($connection));

        $databaseResult = Database::setConnection($connection)
            ->setSql($query->sql)
            ->setParameters($query->parameters)
            ->setValues($this->values)
            ->run();

        return $this->calculateResult($databaseResult, $query);
    }

    public function createConnectionUsingQuery(Query $query)
    {
        $query->loadMissing('connection');

        $connection = $query->connection;

        return Connection::setDatabase($connection->database)
            ->setHost($connection->host)
            ->setPort($connection->port)
            ->setCredential($connection->username, $connection->password)
            ->newConnection();
    }

    public function calculateResult($databaseResult, Query $query)
    {
        if($query->type === 'chart' && $databaseResult->columnCount() !== 2) {
            throw new QueryException($query->id);
        }

        return $databaseResult->fetchAll(PDO::FETCH_OBJ);
    }
}
