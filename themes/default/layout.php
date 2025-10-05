<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config['blog_title'] ?? 'My Blog'); ?></title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($theme_url); ?>/style.css">
    <?php do_hook('head_tags'); ?>
</head>
<body>
    <div class="container">
        <header>
            <div class="site-title">
                <h1><a href="index.php"><?php echo htmlspecialchars($config['blog_title'] ?? 'My Blog'); ?></a></h1>
                <p><?php echo htmlspecialchars($config['blog_description'] ?? 'A minimal blog.'); ?></p>
            </div>
            <?php
            $nav_file = ROOT_PATH . '/data/navigation.json';
            if (file_exists($nav_file)) {
                $nav_links = json_decode(file_get_contents($nav_file), true);
                if (!empty($nav_links)) {
                    echo '<nav class="main-nav">';
                    echo '<ul>';
                    foreach ($nav_links as $link) {
                        echo '<li><a href="' . htmlspecialchars($link['url']) . '">' . htmlspecialchars($link['label']) . '</a></li>';
                    }
                    echo '</ul>';
                    echo '</nav>';
                }
            }
            ?>
        </header>
        <div class="main-content-area">
            <main class="content">
                <?php echo $content; // Page-specific content is injected here ?>
            </main>
            <?php if (is_widget_area_active('sidebar')): ?>
                <aside class="sidebar">
                    <?php render_widget_area('sidebar'); ?>
                </aside>
            <?php endif; ?>
        </div>
        <footer>
            <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($config['blog_title'] ?? 'My Blog'); ?>. All rights reserved.</p>
            <?php if (function_exists('is_logged_in') && is_logged_in()): ?>
                <p><a href="../admin/dashboard.php">Admin</a></p>
            <?php endif; ?>
        </footer>
    </div>
    <?php do_hook('footer_scripts'); ?>
</body>
</html>
