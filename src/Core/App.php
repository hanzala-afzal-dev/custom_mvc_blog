<?php

declare(strict_types=1);

namespace Core;

use FastRoute\Dispatcher;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class App
{
    /**
     * @var array<int, array{method:string, path:string, handler:array{0:class-string,1:string}}>
     */
    private array $routes;

    /**
     * @param array<int, array{method:string, path:string, handler:array{0:class-string,1:string}}> $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $router = new Router($this->routes);

        $result = $router->dispatch($request->getMethod(), (string) $request->getUri());

        if ($result['status'] === Dispatcher::NOT_FOUND) {
            return new Response(404, [], '404 Not Found');
        }

        if ($result['status'] === Dispatcher::METHOD_NOT_ALLOWED) {
            return new Response(405, [], '405 Method Not Allowed');
        }

        /** @var array{0:class-string,1:string} $handler */
        $handler = $result['handler'];

        /** @var array<string, string> $routeParams */
        $routeParams = $result['variables'] ?? [];

        foreach ($routeParams as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        return $this->invoke($handler, $request);
    }

    /**
     * @param array{0:class-string,1:string} $handler
     */
    private function invoke(array $handler, ServerRequestInterface $request): ResponseInterface
    {
        [$controllerClass, $method] = $handler;

        if (!class_exists($controllerClass)) {
            return new Response(500, [], 'Controller not found.');
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            return new Response(500, [], 'Controller method not found.');
        }

        /** @var mixed $response */
        $response = $controller->{$method}($request);

        if (!$response instanceof ResponseInterface) {
            return new Response(500, [], 'Controller must return ResponseInterface.');
        }

        return $response;
    }
}