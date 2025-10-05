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
            <h1><a href="index.php"><?php echo htmlspecialchars($config['blog_title'] ?? 'My Blog'); ?></a></h1>
            <p><?php echo htmlspecialchars($config['blog_description'] ?? 'A minimal blog.'); ?></p>
        </header>
        <main>
            <?php echo $content; // Page-specific content is injected here ?>
        </main>
        <footer>
            <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($config['blog_title'] ?? 'My Blog'); ?>. All rights reserved.</p>
        </footer>
    </div>
    <?php do_hook('footer_scripts'); ?>
</body>
</html>
