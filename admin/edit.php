<?php
// admin/edit.php

// --- DEPENDENCY LOADING ---
// This file loads all of its own dependencies to ensure it works.
require_once __DIR__ . '/auth.php';
require_login();

// Define ROOT_PATH for core functions
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// Load configuration
$config = require_once ROOT_PATH . '/config.php';

// Load Composer's autoloader
if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
    require_once ROOT_PATH . '/vendor/autoload.php';
}

// Load plugins and core functions if they exist
if (file_exists(ROOT_PATH . '/src/plugins.php')) {
    require_once ROOT_PATH . '/src/plugins.php';
    load_plugins($config);
}
if (file_exists(ROOT_PATH . '/src/core.php')) {
    require_once ROOT_PATH . '/src/core.php';
}
// --- END DEPENDENCY LOADING ---


// Get the slug from the URL query string
$slug = $_GET['slug'] ?? null;
if (!$slug) {
    header('Location: dashboard.php'); // Go to dashboard if no slug
    exit;
}

// Fetch the specific post data using the slug
$post = get_post($config, $slug);
if (!$post) {
    header('Location: dashboard.php?error=Post not found.'); // Go to dashboard if post not found
    exit;
}

// Load all available categories for the suggestion list
$categories_json = file_get_contents(ROOT_PATH . '/data/categories.json');
$categories = json_decode($categories_json, true);

// Custom Header
$css_content = file_get_contents(__DIR__ . '/admin.css');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <style><?php echo $css_content; ?></style>
</head>
<body>
<div class="admin-wrapper" style="min-height: 0;">
    <main class="admin-content" style="flex-grow: 1; padding: 20px;">
?>

<div class="page-header">
    <h1>Edit Post</h1>
    <a href="../public/index.php?post=<?php echo htmlspecialchars($post['slug']); ?>" class="btn" target="_blank">View Post</a>
</div>

<div class="card">
    <form action="update-post.php" method="post">
        <input type="hidden" name="original_slug" value="<?php echo htmlspecialchars($post['slug']); ?>">

        <div class="form-group">
            <label for="title">Post Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>">
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" list="category-suggestions" value="<?php echo htmlspecialchars($post['category']); ?>">
        </div>
        <div class="form-group">
            <label for="featured_image">Featured Image URL (Optional)</label>
            <input type="url" id="featured_image" name="featured_image" value="<?php echo htmlspecialchars($post['featured_image'] ?? ''); ?>" placeholder="https://example.com/image.jpg">
            <datalist id="category-suggestions">
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </div>

        <div class="form-group">
            <label for="content">Content (in Markdown)</label>
            <textarea id="content" name="content"><?php echo htmlspecialchars($post['raw_content']); ?></textarea>
        </div>

        <button type="submit" class="btn">Update Post</button>
    </form>
</div>

    </main>
</div>
</body>
</html>
