<?php get_header(); ?>

<article>
    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
    <p><small>Published on <?php echo date('F j, Y', $post['timestamp']); ?></small></p>
    <?php if (!empty($post['featured_image'])) : ?>
        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="featured-image">
    <?php endif; ?>
    <div>
        <?php echo $post['content']; ?>
    </div>
</article>

<?php get_footer(); ?>
