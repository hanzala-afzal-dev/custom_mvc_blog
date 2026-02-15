<?php

declare(strict_types=1);

use App\Controllers\AuthenticationController;

return [
    ['method' => 'POST', 'path' => '/authenticate', 'handler' => [AuthenticationController::class, 'authenticate']],
    ['method' => 'GET', 'path' => '/login', 'handler' => [AuthenticationController::class, 'viewLogin']]
];
