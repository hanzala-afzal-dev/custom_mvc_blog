<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Traits\Session;

class AuthService
{

    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * @throws \Exception
     */
    public function attempt(string $email, string $password): bool
    {
        $userData = $this->user->getByEmail($email);

        if (!is_array($userData) && empty($userData)) {
           return false;
        }

        if (!password_verify($password, $userData['password_hash'])) {
            return false;
        }

        Session::start();
        Session::regenerateId();

        Session::set('auth_user_id', (int) $userData['id']);
        Session::set('auth_role', (string) $userData['role']);

        return true;
    }

    /**
     * @throws \Exception
     */
    public function register(string $name, string $email, string $password): bool
    {
        if ($this->user->getByEmail($email) !== null) {
            return false;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        if (!is_string($passwordHash) || $passwordHash === '') {
            throw new \RuntimeException('Failed to hash password.');
        }

        return $this->user->createUser($name, $email, $passwordHash);

    }


}