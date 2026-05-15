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

    private Dispatcher $dispatcher;

    /**
     * @param array<int, array{method:string, path:string, handler:array{0:class-string,1:string}}> $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->dispatcher = $this->buildDispatcher();
    }

    /**
     * @return array{status:int, handler?:array{0:class-string,1:string}, variables?:array<string, string>}
     */
    public function dispatch(string $method, string $uri): array
    {
        $path = (string) (parse_url($uri, PHP_URL_PATH) ?? '/');
        $routeInfo = $this->dispatcher->dispatch($method, $path);

        return $this->buildDispatchResult($routeInfo);
    }

    private function buildDispatcher(): Dispatcher
    {
        return simpleDispatcher(function (RouteCollector $collector): void {
            foreach ($this->routes as $route) {
                $collector->addRoute(
                    $route['method'],
                    $route['path'],
                    $route['handler']
                );
            }
        });
    }

    /**
     * @param array{0:int, 1?:array{0:class-string,1:string}, 2?:array<string, string>} $routeInfo
     * @return array{status:int, handler?:array{0:class-string,1:string}, variables?:array<string, string>}
     */
    private function buildDispatchResult(array $routeInfo): array
    {
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
