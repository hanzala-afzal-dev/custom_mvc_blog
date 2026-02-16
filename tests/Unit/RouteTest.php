<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Controllers\AuthenticationController;
use Core\Router;
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    public function test_RouteFound(): void
    {
        $routes = require_once  __DIR__. '/../../src/routes.php';

        $router = new Router($routes);

        $routeResult = $router->dispatch('GET', '/login');

        $this->assertIsArray($routeResult);
        $this->assertSame([AuthenticationController::class, 'viewLogin'], $routeResult['handler']);

    }
}