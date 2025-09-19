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

// Include the HTML header
include __DIR__ . '/partials/header.php';
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
            <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($post['title']); ?>">
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" list="category-suggestions" required value="<?php echo htmlspecialchars($post['category']); ?>">
            <datalist id="category-suggestions">
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </div>

        <div class="form-group">
            <label for="content">Content (in Markdown)</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($post['raw_content']); ?></textarea>
        </div>

        <button type="submit" class="btn">Update Post</button>
    </form>
</div>

<?php
// Include the HTML footer
include __DIR__ . '/partials/footer.php';
?>
