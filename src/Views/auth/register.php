<?php

declare(strict_types=1);

use Traits\EscapeString;

/** @var string $csrf */
/** @var string|null $error */
?>
<h1>Register</h1>

<?php if (is_string($error) && $error !== ''): ?>
    <p style="color: red;"><?= EscapeString::html($error) ?></p>
<?php endif; ?>

<form method="POST" action="/register">
    <input type="hidden" name="_csrf" value="<?= EscapeString::html($csrf) ?>">

    <div style="margin-bottom: 8px;">
        <label>Name</label><br>
        <input type="text" name="name" required>
    </div>

    <div style="margin-bottom: 8px;">
        <label>Email</label><br>
        <input type="email" name="email" required>
    </div>

    <div style="margin-bottom: 8px;">
        <label>Password</label><br>
        <input type="password" name="password" minlength="8" required>
    </div>

    <div style="margin-bottom: 8px;">
        <label>Confirm Password</label><br>
        <input type="password" name="password_confirmation" minlength="8" required>
    </div>

    <button type="submit">Register</button>
</form>

<p style="margin-top: 12px;">
    Already have an account? <a href="/login">Go to login</a>
</p>