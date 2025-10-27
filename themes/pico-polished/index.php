<?php get_header(); ?>

<?php foreach ($posts as $post) : ?>
    <article>
        <h2><a href="?post=<?php echo $post['slug']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
        <p><small>Published on <?php echo date('F j, Y', $post['timestamp']); ?></small></p>
        <?php if (!empty($post['featured_image'])) : ?>
            <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="featured-image">
        <?php endif; ?>
        <div>
            <?php echo substr($post['content'], 0, 300); ?>...
            <a href="?post=<?php echo $post['slug']; ?>">Read More</a>
        </div>
    </article>
<?php endforeach; ?>

<?php get_footer(); ?>
