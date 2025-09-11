<?php
// admin/save-post.php

// This script is protected and handles the logic for saving a new post.
require_once 'auth.php';
require_login();

/**
 * Creates a URL-friendly slug from a string.
 * @param string $title The string to convert.
 * @return string The generated slug.
 */
function create_slug(string $title): string
{
    // Convert to lowercase
    $slug = strtolower($title);
    // Replace non-alphanumeric characters with hyphens
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    // Replace whitespace and multiple hyphens with a single hyphen
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    // Trim hyphens from the start and end
    $slug = trim($slug, '-');
    // Append a unique identifier (timestamp) to prevent overwriting
    $slug = $slug . '-' . time();
    return $slug;
}

// --- 1. Verify Request Method ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirect if not a POST request
    header('Location: dashboard.php');
    exit;
}

// --- 2. Get and Validate Form Data ---
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');

if (empty($title) || empty($content)) {
    // For simplicity, we die with an error. A more robust app might redirect
    // back to the form with an error message and repopulated fields.
    exit('Error: Title and content fields cannot be empty.');
}

// --- 3. Generate Slug and Format Content ---
$slug = create_slug($title);
$date = date('Y-m-d');

// Assemble the content for the .md file with front-matter
$file_content = "Title: {$title}\n";
$file_content .= "Date: {$date}\n";
$file_content .= "---\n";
$file_content .= $content;

// --- 4. Save the New Post File ---
$config = require_once __DIR__ . '/../config.php';
$filepath = $config['posts_dir'] . '/' . $slug . '.md';

// Attempt to write the new file
if (file_put_contents($filepath, $file_content) !== false) {
    // --- 5. Redirect on Success ---
    // Redirect back to the dashboard with a success message in the URL
    header('Location: dashboard.php?success=Post was created successfully!');
    exit;
} else {
    // Handle potential file writing errors
    exit('Error: Could not save the post. Please check that the `posts` directory is writable.');
}
