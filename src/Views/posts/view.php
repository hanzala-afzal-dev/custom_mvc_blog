<?php

declare(strict_types=1);

use Traits\EscapeString;
?>

<article>
    <h1><?= EscapeString::html((string) $post['title']); ?></h1>
    <p><strong>Author:</strong> <?= EscapeString::html((string) $post['author_name']); ?></p>
    <p><strong>Created:</strong> <?= EscapeString::html((string) $post['created_at']); ?></p>
    <?php if (!empty($post['updated_at'])): ?>
        <p><strong>Updated:</strong> <?= EscapeString::html((string) $post['updated_at']); ?></p>
    <?php endif; ?>

    <hr>

    <p style="white-space: pre-wrap;"><?= EscapeString::html((string) $post['body']); ?></p>
</article>

<p><a href="/posts">&larr; Back to posts</a></p>
