<?php

declare(strict_types=1);

namespace Database;

use PDO;
use PDOException;

final class Connection
{
    private const DEFAULT_OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    private string $host;
    private string $port;
    private string $user;
    private string $password;
    private string $database;
    private string $charset;
    private string $driver;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->port = $_ENV['DB_PORT'] ?? '3306';
        $this->user = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASSWORD'] ?? '';
        $this->database = $_ENV['DB_DATABASE'] ?? '';
        $this->charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
        $this->driver = $_ENV['DB_DRIVER'] ?? 'mysql';
    }

    private function getDSN(): string
    {
        return sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $this->driver,
            $this->host,
            $this->port,
            $this->database,
            $this->charset
        );
    }

    public function pdo(): PDO
    {
        try {
            return new PDO(
                $this->getDSN(),
                $this->user,
                $this->password,
                self::DEFAULT_OPTIONS
            );
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage());
        }
    }
}