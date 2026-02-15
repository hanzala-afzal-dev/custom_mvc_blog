<?php

declare(strict_types=1);

use App\Controllers\AuthenticationController;

return [
    [
        'method' => 'GET',
        'path' => '/',
        'handler' => [AuthenticationController::class, 'authenticate'],
    ],
];
