<?php
// widgets/categories.php

return [
    'name' => 'Categories',
    'description' => 'Displays a list of post categories.',
    'render' => function() {
        global $config; // The global config is needed for get_all_posts()

        $posts = get_all_posts($config);
        $categories = [];

        foreach ($posts as $post) {
            if (!empty($post['category'])) {
                $categories[] = $post['category'];
            }
        }

        $unique_categories = array_unique($categories);
        sort($unique_categories, SORT_NATURAL | SORT_FLAG_CASE);

        if (empty($unique_categories)) {
            echo '<p>No categories to display.</p>';
            return;
        }

        echo '<ul>';
        foreach ($unique_categories as $category) {
            // The link points to a future category archive page
            echo '<li><a href="index.php?category=' . urlencode($category) . '">' . htmlspecialchars($category) . '</a></li>';
        }
        echo '</ul>';
    }
];