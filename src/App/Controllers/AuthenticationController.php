<?php

declare(strict_types=1);

namespace App\Controllers;


use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\AuthService;
use Traits\Csrf;
use Traits\Session;
use Traits\Render;
use Traits\Permission;

class AuthenticationController
{
    use Csrf;
    use Session;
    use Render;
    use Permission;

    /**
     * Controller to render login page
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
            Session::set('flash_error', 'Email or password is required.');
            return new Response(302, ['Location' => '/login']);
        }

        if ((new AuthService())->attempt($formData['email'], $formData['password'])) {
            return new Response(302, ['Location' => '/posts']);
        }

        Session::start();
        Session::set('flash_error', 'Invalid email or password.');

        return new Response(302, ['Location' => '/login']);
    }

    /**
     * Controller to render registration page
     * @throws \Exception
     */
    public function viewRegistration(): ResponseInterface
    {
        Session::start();

        $error = Session::get('flash_error');

        Session::forget('flash_error');

        $body = $this->render('auth/register', 'layouts/main', [
            'csrf' => $this->token(),
            'error' => is_string($error) ? $error : null,
        ]);

        return new Response(200, ['Content-Type' => 'text/html; charset=UTF-8'], $body);
    }

    /**
     * @throws \Exception
     */
    public function register(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->validateCsrf($request)) {
            Session::set('flash_error', 'No CSRF Token found in request');
            return new Response(302, ['Location' => '/register']);
        }

        $formData = $request->getParsedBody();


        if (empty(trim($formData['name'])) || empty(trim($formData['email'])) || empty(trim($formData['password']))) {
            Session::set('flash_error', 'Name, email and password are required.');
            return new Response(302, ['Location' => '/register']);
        }

        if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            Session::set('flash_error', 'Please provide a valid email address.');
            return new Response(302, ['Location' => '/register']);
        }

        if (strlen($formData['password']) < 8) {
            Session::set('flash_error', 'Password must be at least 8 characters long.');
            return new Response(302, ['Location' => '/register']);
        }

        if ($formData['password'] !== $formData['password_confirmation']) {
            Session::set('flash_error', 'Password confirmation does not match.');
            return new Response(302, ['Location' => '/register']);
        }

        if (!(new AuthService())->register($formData['name'], $formData['email'], $formData['password'])) {
            Session::set('flash_error', 'Email is already in use.');
            return new Response(302, ['Location' => '/register']);
        }

        return new Response(302, ['Location' => '/login']);

    }

    public function logout(): ResponseInterface
    {

        Session::start();

        if(self::isAuthenticated()) {
            Session::forget('auth_user_id');
        }

        return new Response(302, ['Location' => '/login']);

    }
}