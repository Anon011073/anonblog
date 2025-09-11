<?php if (empty($posts)): ?>
    <div class="no-posts">
        <h2>No posts yet</h2>
        <p>There are no posts to display. Check back later!</p>
    </div>
<?php else: ?>
    <div class="post-list">
        <?php foreach ($posts as $post): ?>
            <article class="post-excerpt">
                <h2>
                    <a href="index.php?post=<?php echo htmlspecialchars($post['slug']); ?>">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </a>
                </h2>
                <p class="post-meta">
                    Published on <?php echo date('F j, Y', $post['timestamp']); ?>
                </p>
                <p class="excerpt">
                    <?php echo htmlspecialchars(substr($post['raw_content'], 0, 200)); ?>...
                </p>
                <a href="index.php?post=<?php echo htmlspecialchars($post['slug']); ?>" class="read-more">Read More &rarr;</a>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
