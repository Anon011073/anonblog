<?php

// config.php

/**
 * This file contains the basic configuration for the blog.
 * Edit these values to customize your blog.
 */

return [
    // --- Site Metadata ---
    // Displayed in the site header and browser tab.
    'blog_title' => 'My Minimalist Blog',
    'blog_description' => 'A quiet place for thoughts.',

    // --- Theme Configuration ---
    // The name of the directory in the 'themes' folder that you want to use.
    'active_theme' => 'default',

    // --- Content Directory ---
    // The directory where your Markdown post files are stored.
    'posts_dir' => __DIR__ . '/posts',

    // --- Admin Configuration ---
    // A simple, hardcoded password for the admin area.
    // For a real-world application, you should use a more secure method!
    'admin_password' => 'password',

    // --- Plugin System ---
    // Add the filenames of the plugins you want to enable from the 'plugins' directory.
    'enabled_plugins' => [
        'syntax-highlighter.php',
    ],
];
