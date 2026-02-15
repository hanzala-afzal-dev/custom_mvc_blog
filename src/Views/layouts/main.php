<?php

declare(strict_types=1);

use Traits\EscapeString;
use Traits\Session;
use Traits\Csrf;

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
        <a href="/posts">Posts</a>

        <?php if (is_int(Session::get('auth_user_id'))): ?>
            <form action="/logout" method="post" style="display:inline;">
                <input type="hidden" name="_csrf" value="<?= EscapeString::html(Csrf::token()) ?>">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <a href="/login">Login</a>
        <?php endif; ?>
    </nav>
</header>

<main style="padding: 12px;">
    <?php require $filePath; ?>
</main>
</body>
</html>