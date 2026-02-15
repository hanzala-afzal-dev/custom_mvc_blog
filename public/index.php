<?php

declare(strict_types=1);

use Core\App;
use Dotenv\Dotenv;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

require dirname(__DIR__) . '/vendor/autoload.php';

$projectRoot = dirname(__DIR__);

/**
 * Load .env (DB creds etc.)
 */
if (file_exists($projectRoot . '/.env')) {
    $dotenv = Dotenv::createImmutable($projectRoot);
    $dotenv->safeLoad();
}

/**
 * Create PSR-7 request from PHP superglobals
 */
$psr17Factory = new Psr17Factory();

$requestCreator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactoryInterface
    $psr17Factory, // UriFactoryInterface
    $psr17Factory, // UploadedFileFactoryInterface
    $psr17Factory  // StreamFactoryInterface
);

$request = $requestCreator->fromGlobals();


$app = new App(require_once dirname(__DIR__) . '/src/routes.php');
$response = $app->handle($request);


http_response_code($response->getStatusCode());

foreach ($response->getHeaders() as $headerName => $headerValues) {
    foreach ($headerValues as $headerValue) {
        header($headerName . ': ' . $headerValue, false);
    }
}

echo (string) $response->getBody();