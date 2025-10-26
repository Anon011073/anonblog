<article>
    <header>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p>
            Published on <?php echo date('F j, Y', $post['timestamp']); ?>
            in <?php echo htmlspecialchars($post['category']); ?>
        </p>
    </header>

    <?php if (!empty($post['featured_image'])): ?>
        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
    <?php endif; ?>

    <div>
        <?php echo $post['content']; // Content is pre-rendered HTML from Markdown ?>
    </div>

    <footer>
        <a href="index.php">&larr; Back to Home</a>
    </footer>

    <?php if (function_exists('is_logged_in') && is_logged_in()): ?>
        <div>
            <strong>Admin:</strong>
            <a href="../admin/edit.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">Edit Post</a>
            <a href="../admin/delete.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">Delete Post</a>
        </div>
    <?php endif; ?>
</article>
