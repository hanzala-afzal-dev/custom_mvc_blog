<?php

namespace App\Models;

use Database\Connection;
use PDO;

final class User
{
    /**
     * Table name
     * @var string
     */
    private string $table = 'users';

    /**
     * Primary key of table
     * @var string
     */
    private string $key = 'id';

    /**
     * PDO Instance
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Constructor to initialize PDO instance
     */
    public function __construct()
    {
        $this->pdo = (new Connection())->pdo();
    }

    /**
     * Get User Row using email
     * @param string|null $email
     * @return array|null
     * @throws \Exception
     */
    public function getUserByEmail(?string $email): ?array
    {
        if (empty($email)) {
           throw new \Exception('Trying to find email on null');
        }

        $queryToPrepare = "SELECT * FROM $this->table WHERE email = :email LIMIT 1";

        $statement = $this->pdo->prepare($queryToPrepare);
        
        $statement->bindValue(':email', $email);

        $statement->execute();

        $userData = $statement->fetch();

        return is_array($userData) ? $userData : null;
    }
}
