<article class="full-post">
    <header class="post-header">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <footer>
            <small>
                Published on <?php echo date('F j, Y', $post['timestamp']); ?>
                in <a href="#"><?php echo htmlspecialchars($post['category']); ?></a>
            </small>
        </footer>
    </header>

    <?php if (!empty($post['featured_image'])): ?>
        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width:100%; height:auto; margin-bottom: 1rem;">
    <?php endif; ?>

    <div class="post-content">
        <?php echo $post['content']; // Content is pre-rendered HTML from Markdown ?>
    </div>

    <footer class="post-footer">
        <a href="index.php" role="button" class="secondary">&larr; Back to Home</a>
    </footer>

    <?php if (function_exists('is_logged_in') && is_logged_in()): ?>
        <div class="admin-actions" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #3a3f4b;">
            <strong>Admin:</strong>
            <a href="../admin/edit.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">Edit Post</a>
            <a href="../admin/delete.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="delete-link">Delete Post</a>
        </div>
    <?php endif; ?>
</article>
