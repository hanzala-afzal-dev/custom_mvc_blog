<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;

final class PostService
{
    private Post $post;

    private const PER_PAGE = 5;

    public function __construct()
    {
        $this->post = new Post();
    }

    /**
     * @param array<string, mixed> $queryParams
     * @return array{posts: array<int, array<string, mixed>>, currentPage: int, totalPages: int}
     */
    public function getPaginatedPosts(array $queryParams): array
    {
        $page = isset($queryParams['page']) ? max(1, (int) $queryParams['page']) : 1;
        $totalPosts = $this->post->countAll();
        $totalPages = (int) max(1, (int) ceil($totalPosts / self::PER_PAGE));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * self::PER_PAGE;

        return [
            'posts' => $this->post->paginate(self::PER_PAGE, $offset),
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPostById(int $id): ?array
    {
        return $this->post->getById($id);
    }

    /**
     * @param array<string, mixed> $formData
     * @return array{isValid: bool, title: string, body: string, error: string|null}
     */
    public function validatePostData(array $formData): array
    {
        $title = trim((string) ($formData['title'] ?? ''));
        $body = trim((string) ($formData['body'] ?? ''));

        if ($title === '' || $body === '') {
            return [
                'isValid' => false,
                'title' => $title,
                'body' => $body,
                'error' => 'Title and body are required.',
            ];
        }

        return [
            'isValid' => true,
            'title' => $title,
            'body' => $body,
            'error' => null,
        ];
    }

    public function createPost(int $userId, string $title, string $body): bool
    {
        return $this->post->create($userId, $title, $body);
    }

    public function updatePostForUser(int $postId, int $authUserId, string $authRole, string $title, string $body): bool
    {
        if ($authRole === 'admin') {
            return $this->post->update($postId, $title, $body);
        }

        return $this->post->updateByOwner($postId, $authUserId, $title, $body);
    }

    public function deletePostForUser(int $postId, int $authUserId, string $authRole): bool
    {
        if ($authRole === 'admin') {
            return $this->post->delete($postId);
        }

        return $this->post->deleteByOwner($postId, $authUserId);
    }

    /**
     * @param array<string, mixed> $post
     */
    public function canManagePost(array $post, int $authUserId, string $authRole): bool
    {
        if ($authRole === 'admin') {
            return true;
        }

        return (int) ($post['user_id'] ?? 0) === $authUserId;
    }
}
