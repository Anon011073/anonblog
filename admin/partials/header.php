<?php
// admin/partials/header.php

// This header is the single entry point for all admin pages.
// It handles authentication and loading all core application files.

require_once __DIR__ . '/../auth.php';
require_login();

// Define ROOT_PATH if it's not already defined (for scripts in /admin)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

// Load configuration into the global scope for core functions to use.
global $config;
$config = require_once ROOT_PATH . '/config.php';

// Load the plugin system and activate enabled plugins.
require_once ROOT_PATH . '/src/plugins.php';
load_plugins();

// Load core blog functions.
require_once ROOT_PATH . '/src/core.php';

/**
 * Helper function to determine if a navigation link should be marked as active.
 * It checks if the current script's filename is in the provided array of page names.
 * @param array $page_names An array of script filenames (e.g., ['posts.php', 'create.php']).
 * @return bool True if the current page is one of the given pages, false otherwise.
 */
function is_active_nav(array $page_names): bool
{
    $current_page = basename($_SERVER['PHP_SELF']);
    return in_array($current_page, $page_names);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-header">
            <h2><a href="dashboard.php">My Blog</a></h2>
        </div>

        <nav class="admin-nav">
            <ul>
                <li class="<?php if (is_active_nav(['dashboard.php'])) echo 'active'; ?>">
                    <a href="dashboard.php">Dashboard</a>
                </li>
                <li class="<?php if (is_active_nav(['posts.php', 'create.php', 'edit.php'])) echo 'active'; ?>">
                    <a href="posts.php">Posts</a>
                </li>
                <li class="<?php if (is_active_nav(['themes.php'])) echo 'active'; ?>">
                    <a href="themes.php">Appearance</a>
                </li>
                <li class="<?php if (is_active_nav(['plugins.php'])) echo 'active'; ?>">
                    <a href="plugins.php">Plugins</a>
                </li>
                <li class="<?php if (is_active_nav(['settings.php'])) echo 'active'; ?>">
                    <a href="settings.php">Settings</a>
                </li>
            </ul>
        </nav>

        <div class="admin-sidebar-footer">
            <a href="../public/index.php" target="_blank">View Site</a>
            <span>&nbsp;|&nbsp;</span>
            <a href="logout.php">Logout</a>
        </div>
    </aside>
    <main class="admin-content">

    <!-- The specific page content will start here -->
