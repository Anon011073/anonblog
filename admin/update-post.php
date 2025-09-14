<?php
// admin/update-post.php

// This script handles the submission from the edit form.
require_once 'auth.php';
require_login();

// Load necessary files
$config = require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/plugins.php';
require_once __DIR__ . '/../src/core.php';

// --- 1. Verify Request and Get Data ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirect if not a POST request
    header('Location: dashboard.php');
    exit;
}

$original_slug = $_POST['original_slug'] ?? '';
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$category = trim($_POST['category'] ?? 'General');

// Basic validation
if (empty($original_slug) || empty($title) || empty($content) || empty($category)) {
    exit('Error: All fields are required.');
}

// --- 2. Re-fetch original post to preserve original date ---
// This is a better approach than updating the date on every edit.
$original_post = get_post($config, $original_slug);
$date = $original_post ? date('Y-m-d', $original_post['timestamp']) : date('Y-m-d');


// --- 3. Format the Updated Content ---
$file_content = "Title: {$title}\n";
$file_content .= "Date: {$date}\n"; // Preserve the original date
$file_content .= "Category: {$category}\n";
$file_content .= "---\n";
$file_content .= $content;


// --- 4. Update Categories List (if necessary) ---
// This logic is identical to save-post.php
$categories_file = __DIR__ . '/../data/categories.json';
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


// --- 5. Overwrite the Original Post File ---
$filepath = $config['posts_dir'] . '/' . basename($original_slug) . '.md'; // Use basename for security

if (file_exists($filepath)) {
    if (file_put_contents($filepath, $file_content) !== false) {
        // Success
        header('Location: posts.php?success=Post updated successfully!');
        exit;
    } else {
        // File system error
        exit('Error: Could not update the post. Please check file permissions.');
    }
} else {
    exit('Error: The original post file could not be found.');
}
