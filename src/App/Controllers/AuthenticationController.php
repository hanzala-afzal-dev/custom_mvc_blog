<?php

declare(strict_types=1);

namespace App\Controllers;


use Nyholm\Psr7\Response;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticationController
{
    public function authenticate(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], 'OK');
    }
}