<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light"> <!-- Added for Pico -->
    <title><?php echo htmlspecialchars($config['blog_title'] ?? 'My Blog'); ?></title>
    <!-- Using Pico.css CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <style>
        /* Custom layout styles to complement Pico */
        .main-content-area {
            display: flex;
            gap: 2rem; /* Using rem for better scaling */
        }
        header { margin-top:30px; }
        .content {
            flex-grow: 1;
        }
        .sidebar {
            width: 280px;
            flex-shrink: 0;
        }
        .widget ul {
            padding-left: 0;
            list-style: none;
        }
        .widget li {
            margin-bottom: 0.5rem;
        }
        /* Pico overrides */
        h1 a {
            text-decoration: none;
            color: inherit;
        }
    </style>
    <?php do_hook('head_tags'); ?>
</head>
<body>
    <!-- The .container class is provided by Pico -->
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
                        // Pico styles <nav> elements automatically
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