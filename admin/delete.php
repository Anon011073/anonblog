<?php
// admin/delete.php

// --- DEPENDENCY LOADING ---
require_once __DIR__ . '/auth.php';
require_login();

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

$config = require_once ROOT_PATH . '/config.php';

if (file_exists(ROOT_PATH . '/src/plugins.php')) {
    require_once ROOT_PATH . '/src/plugins.php';
    load_plugins($config);
}
if (file_exists(ROOT_PATH . '/src/core.php')) {
    require_once ROOT_PATH . '/src/core.php';
}
// --- END DEPENDENCY LOADING ---


// Check for slug in either GET or POST request
$slug = $_GET['slug'] ?? $_POST['slug'] ?? null;
if (!$slug) {
    header('Location: dashboard.php');
    exit;
}

$filepath = $config['posts_dir'] . '/' . basename($slug) . '.md';

// --- Handle POST request for actual deletion ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists($filepath)) {
        if (unlink($filepath)) {
            header('Location: ../public/index.php');
            exit;
        } else {
            $error = "Error: Could not delete the post file. Check permissions.";
        }
    } else {
        $error = "Error: Post file not found. It may have already been deleted.";
    }
}

// --- Display GET request confirmation page ---
$post = get_post($config, $slug);
if (!$post) {
    header('Location: dashboard.php?error=Post not found.');
    exit;
}

// Include the HTML header
include __DIR__ . '/partials/header.php';
?>

<div class="page-header">
    <h1>Confirm Deletion</h1>
</div>

<div class="card">
    <?php if (isset($error)): ?>
        <p style="color: #ef4444; font-weight: bold;"><?php echo $error; ?></p>
        <a href="dashboard.php" class="btn">&larr; Back to Dashboard</a>
    <?php else: ?>
        <p>Are you sure you want to permanently delete the following post?</p>
        <p><strong>Title:</strong> <?php echo htmlspecialchars($post['title']); ?></p>

        <form action="delete.php" method="post" style="margin-top: 20px;">
            <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug); ?>">
            <button type="submit" class="btn" style="background-color: #ef4444;">Yes, Delete This Post</button>
            <a href="../public/index.php?post=<?php echo htmlspecialchars($slug); ?>" class="btn" style="background-color: #6c757d; margin-left: 10px;">Cancel</a>
        </form>
    <?php endif; ?>
</div>

<?php
// Include the HTML footer
include __DIR__ . '/partials/footer.php';
?>
