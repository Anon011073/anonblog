<?php
// admin/settings.php
include 'partials/header.php';

$config_file = __DIR__ . '/../config.php';

// --- Handle Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the current config to preserve all other settings
    $config = require $config_file;

    // Update config values from the submitted form
    $config['blog_title'] = trim($_POST['blog_title'] ?? 'My Blog');
    $config['blog_description'] = trim($_POST['blog_description'] ?? '');

    // Write the updated config array back to the file
    $new_config_content = "<?php\n\n// Blog configuration\nreturn " . var_export($config, true) . ";\n";
    if (file_put_contents($config_file, $new_config_content)) {
        // Success: Redirect back with a success message
        header('Location: settings.php?success=Settings saved successfully!');
        exit;
    } else {
        // Error: Could not write to file
        $error = "Failed to save settings. Please check that `config.php` is writable.";
    }
}

// --- Display Page Content ---
$config = require $config_file;
?>

<div class="page-header">
    <h1>General Settings</h1>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="card" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb; margin-bottom: 20px;">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="card" style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; margin-bottom: 20px;">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="card">
    <form action="settings.php" method="post">
        <div class="form-group">
            <label for="blog_title">Site Title</label>
            <input type="text" id="blog_title" name="blog_title" value="<?php echo htmlspecialchars($config['blog_title']); ?>" class="form-control">
        </div>
        <div class="form-group">
            <label for="blog_description">Site Tagline</label>
            <input type="text" id="blog_description" name="blog_description" value="<?php echo htmlspecialchars($config['blog_description']); ?>" class="form-control">
            <p style="font-size: 0.9em; color: #6c757d; margin-top: 5px;">A short description for your blog.</p>
        </div>
        <button type="submit" class="btn">Save Settings</button>
    </form>
</div>

<?php include 'partials/footer.php'; ?>
