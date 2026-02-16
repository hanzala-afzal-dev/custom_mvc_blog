<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Controllers\AuthenticationController;
use App\Controllers\PostController;
use Core\Router;
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    public function test_RouteFound(): void
    {
        $routes = require __DIR__ . '/../../src/routes.php';

        $router = new Router($routes);

        $routeResult = $router->dispatch('GET', '/login');

        $this->assertIsArray($routeResult);
        $this->assertSame([AuthenticationController::class, 'viewLogin'], $routeResult['handler']);
    }

    public function test_PostRoutesFound(): void
    {
        $routes = require __DIR__ . '/../../src/routes.php';

        $router = new Router($routes);

        $listRoute = $router->dispatch('GET', '/posts');
        $editRoute = $router->dispatch('GET', '/posts/10/edit');

        $this->assertSame([PostController::class, 'index'], $listRoute['handler']);
        $this->assertSame([PostController::class, 'showEditForm'], $editRoute['handler']);
    }
}
