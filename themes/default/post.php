<article class="full-post">
    <header class="post-header">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="post-meta">
            Published on <?php echo date('F j, Y', $post['timestamp']); ?>
        </p>
    </header>

    <div class="post-content">
        <?php echo $post['content']; // Content is pre-rendered HTML from Markdown ?>
    </div>

    <footer class="post-footer">
        <a href="index.php">&larr; Back to Home</a>
    </footer>
</article>
