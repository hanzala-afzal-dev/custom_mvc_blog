<?php

declare(strict_types=1);

namespace App\Models;

use Database\Connection;
use PDO;

final class Post
{
    private string $table = 'posts';

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Connection())->pdo();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function paginate(int $limit, int $offset): array
    {
        $sql = "SELECT p.id, p.user_id, p.title, p.body, p.created_at, p.updated_at, u.name AS author_name
                FROM {$this->table} p
                INNER JOIN users u ON u.id = p.user_id
                ORDER BY p.created_at DESC
                LIMIT :limit OFFSET :offset";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        $posts = $statement->fetchAll();

        return is_array($posts) ? $posts : [];
    }

    public function countAll(): int
    {
        $statement = $this->pdo->query("SELECT COUNT(*) FROM {$this->table}");

        $count = $statement->fetchColumn();

        return is_numeric($count) ? (int) $count : 0;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT p.id, p.user_id, p.title, p.body, p.created_at, p.updated_at, u.name AS author_name
                FROM {$this->table} p
                INNER JOIN users u ON u.id = p.user_id
                WHERE p.id = :id
                LIMIT 1";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $post = $statement->fetch();

        return is_array($post) ? $post : null;
    }

    public function create(int $userId, string $title, string $body): bool
    {
        $sql = "INSERT INTO {$this->table} (user_id, title, body) VALUES (:user_id, :title, :body)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':body', $body);

        return $statement->execute();
    }

    public function update(int $id, string $title, string $body): bool
    {
        $sql = "UPDATE {$this->table} SET title = :title, body = :body, updated_at = NOW() WHERE id = :id";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':body', $body);

        return $statement->execute();
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }
}
