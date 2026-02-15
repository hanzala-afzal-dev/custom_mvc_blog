<?php

declare(strict_types=1);

namespace App\Controllers;


use Nyholm\Psr7\Response;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\AuthService;
use Traits\Csrf;
use Traits\Session;
use Traits\Render;

class AuthenticationController
{
    use Csrf;
    use Session;
    use Render;

    /**
     * @throws \Exception
     */
    public function viewLogin(): ResponseInterface
    {
        Session::start();

        $error = Session::get('flash_error');

        Session::forget('flash_error');

        $body = $this->render('auth/login', 'layouts/main', [
            'csrf' => $this->token(),
            'error' => is_string($error) ? $error : null,
        ]);

        return new Response(200, ['Content-Type' => 'text/html; charset=UTF-8'], $body);
    }

    /**
     * @throws \Exception
     */
    public function authenticate(ServerRequestInterface $request): ResponseInterface
    {

        //Improvement: Add validation for email with regex

        if (!$this->validateCsrf($request)) {
            Session::set('flash_error', 'No CSRF Token found in request');
            return new Response(302, ['Location' => '/login']);
        }

        $formData = $request->getParsedBody();
        if (empty($formData['email']) || empty($formData['password'])) {
            throw new \Exception('Email or password is required');
        }

        if ((new AuthService())->attempt($formData['email'], $formData['password'])) {
            return new Response(302, ['Location' => '/posts']);
        }

        Session::start();
        Session::set('flash_error', 'Invalid email or password.');

        return new Response(302, ['Location' => '/login']);
    }
}