<?php

declare(strict_types=1);

use Traits\Session;
use Traits\Permission;

Session::start();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MVC Blog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<header style="padding: 12px; border-bottom: 1px solid #ddd;">
    <nav>
        <?php if (Permission::isAuthenticated()): ?>
            <a href="/posts">Posts</a>
            <a href="/logout"><button type="submit">Logout</button></a>
        <?php else: ?>
            <a href="/login">Login</a>
            <a href="/registration" style="margin-left: 8px;">Register</a>
        <?php endif; ?>
    </nav>
</header>

<main style="padding: 12px;">
    <?php require $filePath; ?>
</main>
</body>
</html>