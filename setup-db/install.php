<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use Database\Connection;


const TABLES_SQL_FILE = __DIR__ . "/../setup-db/tables.sql";
const SEED_SQL_FILE = __DIR__ . "/../setup-db/seed.sql";

$connection = new Connection();
$pdoInstance = $connection->pdo();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '3306';
$database = $_ENV['DB_DATABASE'] ?? 'custom_mvc_blog';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';
$charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

$pdoInstance->exec(sprintf('CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET %s COLLATE %s', $database, $charset, 'utf8mb4_unicode_ci'));

$pdoInstance->exec(sprintf('USE %s', $database));

if (!file_exists(TABLES_SQL_FILE)) {
    throw new RuntimeException('Tables SQL file not found');
}

$tablesSql = file_get_contents(TABLES_SQL_FILE);

if(empty($tablesSql)) {
    throw new RuntimeException('Tables sql file is empty');
}

$pdoInstance->exec($tablesSql);
#####################################################################
if (!file_exists(SEED_SQL_FILE)) {
    throw new RuntimeException('Seeds SQL file not found');
}

$seedSql = file_get_contents(SEED_SQL_FILE);

if(empty($seedSql)) {
    throw new RuntimeException('Seeds sql file is empty');
}

$pdoInstance->exec($seedSql);

echo 'Database setup completed'.PHP_EOL;