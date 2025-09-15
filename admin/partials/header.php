<?php
// admin/partials/header.php

// This partial now only handles auth and the HTML structure.
// Core dependencies must be loaded by each page individually.
require_once __DIR__ . '/../auth.php';
require_login();

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
                <li class="<?php if (is_active_nav(['create.php'])) echo 'active'; ?>">
                    <a href="create.php">Add New Post</a>
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
