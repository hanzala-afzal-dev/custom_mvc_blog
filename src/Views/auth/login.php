<?php

declare(strict_types=1);

use Traits\EscapeString;

/** @var string $csrf */
/** @var string|null $error */
?>
<h1>Login</h1>

<?php if (is_string($error) && $error !== ''): ?>
    <p style="color: red;"><?= EscapeString::html($error) ?></p>
<?php endif; ?>

<form method="POST" action="/authenticate">
    <input type="hidden" name="_csrf" value="<?= EscapeString::html($csrf) ?>">

    <div style="margin-bottom: 8px;">
        <label>Email</label><br>
        <input type="email" name="email" required>
    </div>

    <div style="margin-bottom: 8px;">
        <label>Password</label><br>
        <input type="password" name="password" required>
    </div>

    <button type="submit">Login</button>
</form>
