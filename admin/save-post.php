<?php
// admin/save-post.php

// This script handles the logic for saving a new post.
// It is a standalone handler and does not render HTML.

// Enable detailed error reporting for debugging this specific issue
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'auth.php';
require_login();

// Load dependencies
$config = require_once __DIR__ . '/../config.php';

/**
 * Creates a URL-friendly slug from a string.
 * @param string $title The string to convert.
 * @return string The generated slug.
 */
function create_slug(string $title): string
{
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    $slug = empty($slug) ? 'post' : $slug; // Ensure slug is not empty
    $slug = $slug . '-' . time();
    return $slug;
}

// --- 1. Verify Request Method ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

// --- 2. Get and Validate Form Data ---
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$category = trim($_POST['category'] ?? 'General');
$featured_image = trim($_POST['featured_image'] ?? ''); // Added

if (empty($title) || empty($content) || empty($category)) {
    exit('Error: Title, content, and category fields cannot be empty.');
}

// --- 3. Generate Slug and Format Content ---
$slug = create_slug($title);
$date = date('Y-m-d');

$file_content = "Title: {$title}\n";
$file_content .= "Date: {$date}\n";
$file_content .= "Category: {$category}\n";
if (!empty($featured_image)) {
    $file_content .= "Featured Image: {$featured_image}\n";
}
$file_content .= "---\n";
$file_content .= $content;

// --- 4. Update Categories List ---
$categories_file = __DIR__ . '/../data/categories.json';
if (is_readable($categories_file) && is_writable($categories_file)) {
    $categories_list = json_decode(file_get_contents($categories_file), true);
    $found = false;
    foreach ($categories_list as $existing_category) {
        if (strcasecmp($existing_category, $category) === 0) {
            $found = true;
            break;
        }
    }
    if (!$found) {
        $categories_list[] = $category;
        sort($categories_list, SORT_NATURAL | SORT_FLAG_CASE);
        file_put_contents($categories_file, json_encode($categories_list, JSON_PRETTY_PRINT));
    }
}

// --- 5. Save the New Post File ---
$filepath = $config['posts_dir'] . '/' . $slug . '.md';

// Use error control operator and check error_get_last() for a specific error.
if (@file_put_contents($filepath, $file_content) !== false) {
    // Redirect to the new post's public URL for immediate confirmation.
    header('Location: ../public/index.php?post=' . urlencode($slug));
    exit;
} else {
    $error = error_get_last();
    $error_message = "Error: Could not save the post. ";
    if ($error !== null) {
        $error_message .= "PHP Error: " . $error['message'];
    } else {
        $error_message .= "An unknown error occurred. Please check server logs and file permissions.";
    }
    exit($error_message);
}