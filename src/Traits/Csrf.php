<?php

namespace Traits;

use Psr\Http\Message\ServerRequestInterface;

trait Csrf
{
    private static string $session_key = '_csrf_token';

    /**
     * @throws Exception
     */
    public static function token(): string
    {
        Session::start();

        $token = Session::get(self::$session_key);
        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            Session::set(self::$session_key, $token);
        }

        return $token;
    }

    public function validateCsrf(ServerRequestInterface $request): bool
    {
        if (!$this->hasCsrfToken($request)) {
            return false;
        }

        Session::start();

        $tokenFromRequest = $request->getParsedBody()['_csrf'] ?? null;

        $tokenFromSession = Session::get(self::$session_key);
        if (!is_string($tokenFromSession) || $tokenFromSession === '') {
            return false;
        }

        if (!is_string($tokenFromRequest) || $tokenFromRequest === '') {
            return false;
        }

        return hash_equals($tokenFromRequest, $tokenFromSession);
    }

    private function hasCsrfToken(ServerRequestInterface $request): bool
    {
        $parsedBody = $request->getParsedBody();
        if (!is_array($parsedBody) || empty($parsedBody['_csrf'])) {
            return false;
        }

        return true;
    }

}
