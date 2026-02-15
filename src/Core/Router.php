<?php

declare(strict_types=1);

namespace Core;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

final class Router
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

    /**
     * @return array{status:int, handler?:array{0:class-string,1:string}, variables?:array<string, string>}
     */
    public function dispatch(string $method, string $uri): array
    {
        $path = (string) (parse_url($uri, PHP_URL_PATH) ?? '/');

        $dispatcher = simpleDispatcher(function (RouteCollector $collector): void {
            foreach ($this->routes as $route) {
                $collector->addRoute(
                    $route['method'],
                    $route['path'],
                    $route['handler']
                );
            }
        });

        $routeInfo = $dispatcher->dispatch($method, $path);

        if ($routeInfo[0] === Dispatcher::NOT_FOUND) {
            return ['status' => Dispatcher::NOT_FOUND];
        }

        if ($routeInfo[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            return ['status' => Dispatcher::METHOD_NOT_ALLOWED];
        }

        /** @var array{0:class-string,1:string} $handler */
        $handler = $routeInfo[1];

        /** @var array<string, string> $variables */
        $variables = $routeInfo[2];

        return [
            'status' => Dispatcher::FOUND,
            'handler' => $handler,
            'variables' => $variables,
        ];
    }
}