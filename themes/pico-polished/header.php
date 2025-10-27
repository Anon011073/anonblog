<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <title><?php echo htmlspecialchars($config['blog_title'] ?? 'My Blog'); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <link rel="stylesheet" href="<?php echo get_theme_file_uri('style.css'); ?>">
    <?php do_hook('head_tags'); ?>
</head>
<body>
    <div class="container">
        <header>
             <div class="grid">
                <div>
                    <h1><a href="index.php"><?php echo htmlspecialchars($config['blog_title'] ?? 'My Blog'); ?></a></h1>
                    <p><?php echo htmlspecialchars($config['blog_description'] ?? 'A minimal blog.'); ?></p>
                </div>
                <?php
                $nav_file = ROOT_PATH . '/data/navigation.json';
                if (file_exists($nav_file)) {
                    $nav_links = json_decode(file_get_contents($nav_file), true);
                    if (!empty($nav_links)) {
                        echo '<nav><ul>';
                        foreach ($nav_links as $link) {
                            echo '<li><a href="' . htmlspecialchars($link['url']) . '">' . htmlspecialchars($link['label']) . '</a></li>';
                        }
                        echo '</ul></nav>';
                    }
                }
                ?>
            </div>
        </header>
        <div class="grid">
            <main>
