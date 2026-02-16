<?php

declare(strict_types=1);

use App\Controllers\AuthenticationController;
use App\Controllers\PostController;

return [
    ['method' => 'GET', 'path' => '/login', 'handler' => [AuthenticationController::class, 'viewLogin']],
    ['method' => 'POST', 'path' => '/authenticate', 'handler' => [AuthenticationController::class, 'authenticate']],
    ['method' => 'GET', 'path' => '/registration', 'handler' => [AuthenticationController::class, 'viewRegistration']],
    ['method' => 'POST', 'path' => '/register', 'handler' => [AuthenticationController::class, 'register']],
    ['method' => 'GET', 'path' => '/logout', 'handler' => [AuthenticationController::class, 'logout']],
    ['method' => 'GET', 'path' => '/posts', 'handler' => [PostController::class, 'index']],
    ['method' => 'GET', 'path' => '/posts/create', 'handler' => [PostController::class, 'showCreateForm']],
    ['method' => 'POST', 'path' => '/posts', 'handler' => [PostController::class, 'store']],
    ['method' => 'GET', 'path' => '/posts/{id:\d+}', 'handler' => [PostController::class, 'view']],
    ['method' => 'GET', 'path' => '/posts/{id:\d+}/edit', 'handler' => [PostController::class, 'showEditForm']],
    ['method' => 'POST', 'path' => '/posts/{id:\d+}/update', 'handler' => [PostController::class, 'update']],
    ['method' => 'POST', 'path' => '/posts/{id:\d+}/delete', 'handler' => [PostController::class, 'delete']],
];