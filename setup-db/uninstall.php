<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use Database\Connection;

$connection = new Connection();
$pdoInstance = $connection->pdo();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '3306';
$database = $_ENV['DB_DATABASE'] ?? 'custom_mvc_blog';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';
$charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

$pdoInstance->exec(sprintf('USE %s', $database));

$pdoInstance->exec(sprintf('DROP DATABASE IF EXISTS `%s`', $database));

echo 'Database dropped.' . PHP_EOL;