<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\PostService;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Traits\Csrf;
use Traits\Permission;
use Traits\Render;
use Traits\Session;

final class PostController
{
    use Csrf;
    use Permission;
    use Render;
    use Session;

    private PostService $postService;

    public function __construct()
    {
        $this->postService = new PostService();
    }

    /**
     * @throws \Exception
     */
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->isAuthenticated()) {
            return new Response(302, ['Location' => '/login']);
        }

        $postData = $this->postService->getPaginatedPosts($request->getQueryParams());

        $body = $this->render('posts/index', 'layouts/main', [
            'posts' => $postData['posts'],
            'currentPage' => $postData['currentPage'],
            'totalPages' => $postData['totalPages'],
            'csrf' => $this->token(),
            'flashSuccess' => $this->pullFlash('flash_success'),
            'flashError' => $this->pullFlash('flash_error'),
            'authUserId' => (int) Session::get('auth_user_id', 0),
            'authRole' => (string) Session::get('auth_role', 'normal'),
        ]);

        return new Response(200, ['Content-Type' => 'text/html; charset=UTF-8'], $body);
    }

    /**
     * @throws \Exception
     */
    public function showCreateForm(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->isAuthenticated()) {
            return new Response(302, ['Location' => '/login']);
        }

        $body = $this->render('posts/form', 'layouts/main', [
            'csrf' => $this->token(),
            'action' => '/posts',
            'submitLabel' => 'Create Post',
            'pageTitle' => 'Create Post',
            'post' => ['title' => '', 'body' => ''],
            'flashError' => $this->pullFlash('flash_error'),
        ]);

        return new Response(200, ['Content-Type' => 'text/html; charset=UTF-8'], $body);
    }

    /**
     * @throws \Exception
     */
    public function store(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->isAuthenticated() || !$this->isAllowed('add_post')) {
            Session::set('flash_error', 'You are not allowed to add posts.');
            return new Response(302, ['Location' => '/posts']);
        }

        if (!$this->validateCsrf($request)) {
            Session::set('flash_error', 'Invalid CSRF token.');
            return new Response(302, ['Location' => '/posts/create']);
        }

        $validation = $this->postService->validatePostData((array) $request->getParsedBody());
        if (!$validation['isValid']) {
            Session::set('flash_error', (string) $validation['error']);
            return new Response(302, ['Location' => '/posts/create']);
        }

        $this->postService->createPost(
            (int) Session::get('auth_user_id', 0),
            $validation['title'],
            $validation['body']
        );

        Session::set('flash_success', 'Post created successfully.');

        return new Response(302, ['Location' => '/posts']);
    }

    /**
     * @throws \Exception
     */
    public function view(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->isAuthenticated()) {
            return new Response(302, ['Location' => '/login']);
        }

        $post = $this->postService->getPostById((int) $request->getAttribute('id'));
        if ($post === null) {
            return new Response(404, [], 'Post not found');
        }

        $body = $this->render('posts/view', 'layouts/main', ['post' => $post]);

        return new Response(200, ['Content-Type' => 'text/html; charset=UTF-8'], $body);
    }

    /**
     * @throws \Exception
     */
    public function showEditForm(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->isAuthenticated()) {
            return new Response(302, ['Location' => '/login']);
        }

        $id = (int) $request->getAttribute('id');
        $post = $this->postService->getPostById($id);
        if ($post === null) {
            return new Response(404, [], 'Post not found');
        }

        if (!$this->postService->canManagePost($post, (int) Session::get('auth_user_id', 0), (string) Session::get('auth_role', 'normal'))) {
            Session::set('flash_error', 'You are not allowed to edit this post.');
            return new Response(302, ['Location' => '/posts']);
        }

        $body = $this->render('posts/form', 'layouts/main', [
            'csrf' => $this->token(),
            'action' => '/posts/' . $id . '/update',
            'submitLabel' => 'Update Post',
            'pageTitle' => 'Edit Post',
            'post' => $post,
            'flashError' => $this->pullFlash('flash_error'),
        ]);

        return new Response(200, ['Content-Type' => 'text/html; charset=UTF-8'], $body);
    }

    /**
     * @throws \Exception
     */
    public function update(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->isAuthenticated() || !$this->isAllowed('update_post')) {
            Session::set('flash_error', 'You are not allowed to update posts.');
            return new Response(302, ['Location' => '/posts']);
        }

        if (!$this->validateCsrf($request)) {
            Session::set('flash_error', 'Invalid CSRF token.');
            return new Response(302, ['Location' => '/posts']);
        }

        $id = (int) $request->getAttribute('id');
        $post = $this->postService->getPostById($id);
        if ($post === null) {
            return new Response(404, [], 'Post not found');
        }

        if (!$this->postService->canManagePost($post, (int) Session::get('auth_user_id', 0), (string) Session::get('auth_role', 'normal'))) {
            Session::set('flash_error', 'You are not allowed to edit this post.');
            return new Response(302, ['Location' => '/posts']);
        }

        $validation = $this->postService->validatePostData((array) $request->getParsedBody());
        if (!$validation['isValid']) {
            Session::set('flash_error', (string) $validation['error']);
            return new Response(302, ['Location' => '/posts/' . $id . '/edit']);
        }

        $this->postService->updatePost($id, $validation['title'], $validation['body']);

        Session::set('flash_success', 'Post updated successfully.');

        return new Response(302, ['Location' => '/posts']);
    }

    /**
     * @throws \Exception
     */
    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->isAuthenticated() || !$this->isAllowed('delete_post')) {
            Session::set('flash_error', 'You are not allowed to delete posts.');
            return new Response(302, ['Location' => '/posts']);
        }

        if (!$this->validateCsrf($request)) {
            Session::set('flash_error', 'Invalid CSRF token.');
            return new Response(302, ['Location' => '/posts']);
        }

        $id = (int) $request->getAttribute('id');
        $post = $this->postService->getPostById($id);
        if ($post === null) {
            return new Response(404, [], 'Post not found');
        }

        if (!$this->postService->canManagePost($post, (int) Session::get('auth_user_id', 0), (string) Session::get('auth_role', 'normal'))) {
            Session::set('flash_error', 'You are not allowed to delete this post.');
            return new Response(302, ['Location' => '/posts']);
        }

        $this->postService->deletePost($id);
        Session::set('flash_success', 'Post deleted successfully.');

        return new Response(302, ['Location' => '/posts']);
    }

    private function pullFlash(string $key): ?string
    {
        Session::start();
        $message = Session::get($key);
        Session::forget($key);

        return is_string($message) ? $message : null;
    }
}
