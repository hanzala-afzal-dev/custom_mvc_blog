<?php

declare(strict_types=1);

use Traits\EscapeString;
?>

<h1>Posts</h1>

<?php if (!empty($flashSuccess)): ?>
    <p style="color: green;"><?= EscapeString::html($flashSuccess); ?></p>
<?php endif; ?>

<?php if (!empty($flashError)): ?>
    <p style="color: red;"><?= EscapeString::html($flashError); ?></p>
<?php endif; ?>

<p><a href="/posts/create">Add new post</a></p>

<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <thead>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($posts)): ?>
        <tr>
            <td colspan="5">No posts found.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= (int) $post['id']; ?></td>
                <td><?= EscapeString::html((string) $post['title']); ?></td>
                <td><?= EscapeString::html((string) $post['author_name']); ?></td>
                <td><?= EscapeString::html((string) $post['created_at']); ?></td>
                <td>
                    <a href="/posts/<?= (int) $post['id']; ?>">View</a>
                    <?php if ((int) $authUserId === (int) $post['user_id'] || $authRole === 'admin'): ?>
                        | <a href="/posts/<?= (int) $post['id']; ?>/edit">Edit</a>
                        | <form action="/posts/<?= (int) $post['id']; ?>/delete" method="post" style="display:inline;">
                            <input type="hidden" name="_csrf" value="<?= EscapeString::html($csrf); ?>">
                            <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<?php if ($totalPages > 1): ?>
    <div style="margin-top: 16px;">
        <?php if ($currentPage > 1): ?>
            <a href="/posts?page=<?= $currentPage - 1; ?>">&laquo; Previous</a>
        <?php endif; ?>

        <span style="margin: 0 8px;">Page <?= $currentPage; ?> of <?= $totalPages; ?></span>

        <?php if ($currentPage < $totalPages): ?>
            <a href="/posts?page=<?= $currentPage + 1; ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
