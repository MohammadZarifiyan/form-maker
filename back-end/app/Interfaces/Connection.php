<?php

namespace App\Interfaces;

use PDO;

interface Connection
{
    public function newConnection(): static;
    public function setDatabase(string $database): static;
    public function setHost(string $host): static;
    public function setPort(int $port): static;
    public function setCredential(string $username, string|null $password): static;
    public function getDsn(): string;
    public function singleton(): PDO;
}
