<article class="full-post">
    <header class="post-header">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="post-meta">
            Published on <?php echo date('F j, Y', $post['timestamp']); ?>
            in <span class="category"><?php echo htmlspecialchars($post['category']); ?></span>
        </p>
    </header>

    <div class="post-content">
        <?php if (function_exists('is_logged_in') && is_logged_in()): ?>
            <div class="admin-actions">
                <strong>Admin:</strong>
                <a href="admin/edit.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">Edit Post</a>
                <a href="admin/delete.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="delete-link">Delete Post</a>
            </div>
        <?php endif; ?>

        <?php echo $post['content']; // Content is pre-rendered HTML from Markdown ?>
    </div>

    <footer class="post-footer">
        <a href="index.php">&larr; Back to Home</a>
    </footer>
</article>
