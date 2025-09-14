<?php
// admin/save-post.php

// This script is protected and handles the logic for saving a new post.
require_once 'auth.php';
require_login();

// The $config global is now loaded via header.php, but this page doesn't include the header.
// It's a standalone script that handles a form submission.
// Therefore, it needs to load the config itself.
// The previous fix was correct for this file. Let's re-verify.
// No, the header.php should be included in all admin pages.
// save-post.php and update-post.php are not pages, they are handlers.
// My plan to centralize everything in header.php was flawed for scripts that don't produce HTML.

// Let's re-read the plan.
// "I will remove the individual require_once statements for core files from posts.php, edit.php, delete.php, save-post.php, and update-post.php, as they will now be handled by the shared header."
// This is incorrect for save-post.php and update-post.php, as they don't and shouldn't include a file that outputs a full HTML header.

// I need to adjust my plan. `save-post.php` and `update-post.php` must load their dependencies themselves.
// The bug I fixed in them earlier (loading config once at the top) was the correct pattern for them.
// I need to check if I have broken them with my recent changes.

// Let's read `admin/save-post.php` again.
read_file('admin/save-post.php')

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
$category = trim($_POST['category'] ?? 'General');

if (empty($title) || empty($content) || empty($category)) {
    // For simplicity, we die with an error. A more robust app might redirect
    // back to the form with an error message and repopulated fields.
    exit('Error: Title, content, and category fields cannot be empty.');
}

// --- 3. Generate Slug and Format Content ---
$slug = create_slug($title);
$date = date('Y-m-d');

// Assemble the content for the .md file with front-matter
$file_content = "Title: {$title}\n";
$file_content .= "Date: {$date}\n";
$file_content .= "Category: {$category}\n";
$file_content .= "---\n";
$file_content .= $content;

// --- 4. Update Categories List (if necessary) ---
$categories_file = __DIR__ . '/../data/categories.json';
$categories_list = json_decode(file_get_contents($categories_file), true);

// Check if the category exists (case-insensitive)
$found = false;
foreach ($categories_list as $existing_category) {
    if (strcasecmp($existing_category, $category) === 0) {
        $found = true;
        break;
    }
}

if (!$found) {
    $categories_list[] = $category;
    sort($categories_list, SORT_NATURAL | SORT_FLAG_CASE); // Sort naturally
    file_put_contents($categories_file, json_encode($categories_list, JSON_PRETTY_PRINT));
}

// --- 5. Save the New Post File ---
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
