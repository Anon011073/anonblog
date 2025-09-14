<?php
// admin/delete.php
include 'partials/header.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/plugins.php';
require_once __DIR__ . '/../src/core.php';

// Check for slug in either GET or POST request
$slug = $_GET['slug'] ?? $_POST['slug'] ?? null;
if (!$slug) {
    header('Location: posts.php');
    exit;
}

$config = require_once __DIR__ . '/../config.php';
$filepath = $config['posts_dir'] . '/' . basename($slug) . '.md'; // Use basename for security

// --- Handle POST request for actual deletion ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic CSRF check could be added here in a real app
    if (file_exists($filepath)) {
        if (unlink($filepath)) {
            // Success
            header('Location: posts.php?success=Post was deleted successfully.');
            exit;
        } else {
            // File system error
            $error = "Error: Could not delete the post file. Check permissions.";
        }
    } else {
        // File not found, maybe already deleted
        $error = "Error: Post file not found. It may have already been deleted.";
    }
}

// --- Display GET request confirmation page ---
$post = get_post($slug);
// If post doesn't exist (e.g., invalid slug), redirect
if (!$post) {
    header('Location: posts.php?error=Post not found.');
    exit;
}
?>

<div class="page-header">
    <h1>Confirm Deletion</h1>
</div>

<div class="card">
    <?php if (isset($error)): ?>
        <p style="color: #ef4444; font-weight: bold;"><?php echo $error; ?></p>
        <a href="posts.php" class="btn">&larr; Back to Posts</a>
    <?php else: ?>
        <p>Are you sure you want to permanently delete the following post?</p>
        <p><strong>Title:</strong> <?php echo htmlspecialchars($post['title']); ?></p>
        <p><strong>File:</strong> <?php echo htmlspecialchars($slug . '.md'); ?></p>

        <form action="delete.php" method="post" style="margin-top: 20px;">
            <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug); ?>">
            <button type="submit" class="btn" style="background-color: #ef4444;">Yes, Delete This Post</button>
            <a href="posts.php" class="btn" style="background-color: #6c757d; margin-left: 10px;">Cancel</a>
        </form>
    <?php endif; ?>
</div>

<?php
include 'partials/footer.php';
?>
