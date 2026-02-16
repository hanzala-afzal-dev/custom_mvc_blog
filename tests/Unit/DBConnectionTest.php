<?php

declare(strict_types=1);

namespace Tests\Unit;

use PDO;
use PDOException;
use Database\Connection;
use PHPUnit\Framework\TestCase;

final class DBConnectionTest extends TestCase
{
    public function test_DBConnectionPDOInstance(): void
    {
        $connectionInstance = new Connection();
        $pdo = $connectionInstance->pdo();

        $this->assertInstanceOf(PDO::class, $pdo);
    }

    public function test_DBConnectionThrowException(): void
    {
        $_ENV['DB_DATABASE'] = 'wrong_db';
        $connectionInstance = new Connection();

        $this->expectException(PDOException::class);
        $this->expectExceptionMessage('Database connection failed:');

        try {
            $connectionInstance->pdo();
        } finally {
            $_ENV['DB_DRIVER'] = 'mysql';
        }
    }

}