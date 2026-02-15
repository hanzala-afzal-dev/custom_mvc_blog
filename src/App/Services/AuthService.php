<?php

namespace App\Services;

use App\Models\User;
use Traits\Session;

class AuthService
{
    /**
     * @var User
     */
    private User $user;

    /**
     * @throws \Exception
     */
    public function attempt(string $email, string $password): bool
    {
        $userData = (new User())->getUserByEmail($email);

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


}