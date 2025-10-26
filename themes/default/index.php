<?php if (empty($posts)): ?>
    <div class="no-posts">
        <h2>No posts yet</h2>
        <p>There are no posts to display. Check back later!</p>
    </div>
<?php else: ?>
    <div class="post-list">
        <?php foreach ($posts as $post): ?>
            <article class="post-excerpt">
                <?php if (!empty($post['featured_image'])): ?>
                    <a href="index.php?post=<?php echo htmlspecialchars($post['slug']); ?>">
                        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width:100%; height:auto; margin-bottom: 1rem;">
                    </a>
                <?php endif; ?>
                <h2>
                    <a href="index.php?post=<?php echo htmlspecialchars($post['slug']); ?>">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </a>
                </h2>
                <p class="post-meta">
                    Published on <?php echo date('F j, Y', $post['timestamp']); ?>
                    in <span class="category"><?php echo htmlspecialchars($post['category']); ?></span>
                </p>
                <p class="excerpt">
                    <?php echo htmlspecialchars(substr($post['raw_content'], 0, 200)); ?>...
                </p>
                <a href="index.php?post=<?php echo htmlspecialchars($post['slug']); ?>" class="read-more">Read More &rarr;</a>
            </article>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($pagination) && isset($pagination['total_pages']) && $pagination['total_pages'] > 1): ?>
        <nav class="pagination" aria-label="Pagination" style="margin-top: 2rem; text-align: center;">
            <?php if ($pagination['style'] === 'numbered'): ?>
                <ul>
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li>
                            <?php if ($i == $pagination['current_page']): ?>
                                <a href="?paged=<?php echo $i; ?>" aria-current="page"><?php echo $i; ?></a>
                            <?php else: ?>
                                <a href="?paged=<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endfor; ?>
                </ul>
            <?php elseif ($pagination['style'] === 'load_more' && $pagination['current_page'] < $pagination['total_pages']): ?>
                <a href="?paged=<?php echo $pagination['current_page'] + 1; ?>" role="button">Load More</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

<?php endif; ?>