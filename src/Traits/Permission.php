<?php

namespace Traits;

use Traits\Session;

trait Permission
{
    use Session;

    private array $permissions = [
        'admin' => ['add_comment', 'update_comment', 'delete_comment', 'delete_post'],
        'normal' => ['add_comment', 'update_comment', 'delete_comment', 'delete_post', 'add_post' , 'update_post', 'delete_post'],
    ];

    public function isAuthenticated(): bool
    {
        $userId = Session::get('auth_user_id');
        if (!is_int($userId) || $userId <= 0) {
            return false;
            //could also redirect user back from here
        }
        return true;
    }

    public function isAllowed(?string $permission): bool
    {
        $userRole = Session::get('auth_role');
        if (array_key_exists($userRole, $this->permissions) && in_array($permission, $this->permissions[$userRole])) {
            return true;
        }

        return false;
    }
}