<?php
// admin/edit.php
include 'partials/header.php';

// Load Core Dependencies
$config = require_once __DIR__ . '/../config.php';
if (file_exists(__DIR__ . '/../src/plugins.php')) {
    require_once __DIR__ . '/../src/plugins.php';
    load_plugins($config);
}
if (file_exists(__DIR__ . '/../src/core.php')) {
    require_once __DIR__ . '/../src/core.php';
}

// Get the slug from the URL query string
$slug = $_GET['slug'] ?? null;
if (!$slug) {
    // Redirect if no slug is provided
    header('Location: posts.php');
    exit;
}

// Fetch the specific post data using the slug
$post = get_post($config, $slug);
if (!$post) {
    // Redirect if post is not found
    header('Location: posts.php?error=Post not found.');
    exit;
}

// Load all available categories for the suggestion list
$categories_json = file_get_contents(__DIR__ . '/../data/categories.json');
$categories = json_decode($categories_json, true);
?>

<div class="page-header">
    <h1>Edit Post</h1>
    <a href="posts.php" class="btn">&larr; Back to All Posts</a>
</div>

<div class="card">
    <form action="update-post.php" method="post">
        <!-- Hidden input to identify which post is being updated -->
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
            <!-- We use the raw_content to edit the original Markdown source -->
            <textarea id="content" name="content" required><?php echo htmlspecialchars($post['raw_content']); ?></textarea>
        </div>

        <button type="submit" class="btn">Update Post</button>
    </form>
</div>

<?php
include 'partials/footer.php';
?>
