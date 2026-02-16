<?php

declare(strict_types=1);

use Traits\EscapeString;
?>

<h1><?= EscapeString::html((string) $pageTitle); ?></h1>

<?php if (!empty($flashError)): ?>
    <p style="color: red;"><?= EscapeString::html($flashError); ?></p>
<?php endif; ?>

<form method="post" action="<?= EscapeString::html((string) $action); ?>">
    <input type="hidden" name="_csrf" value="<?= EscapeString::html((string) $csrf); ?>">

    <div style="margin-bottom: 12px;">
        <label for="title">Title</label><br>
        <input id="title" name="title" type="text" required value="<?= EscapeString::html((string) ($post['title'] ?? '')); ?>" style="width: 100%; max-width: 600px;">
    </div>

    <div style="margin-bottom: 12px;">
        <label for="body">Body</label><br>
        <textarea id="body" name="body" rows="10" required style="width: 100%; max-width: 600px;"><?= EscapeString::html((string) ($post['body'] ?? '')); ?></textarea>
    </div>

    <button type="submit"><?= EscapeString::html((string) $submitLabel); ?></button>
    <a href="/posts" style="margin-left: 8px;">Cancel</a>
</form>
