<?php
// admin/settings.php
include 'partials/header.php';

$config_file = __DIR__ . '/../config.php';

// --- Handle Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the raw content of the config file
    $config_content = file_get_contents($config_file);

    // An array of settings to update
    $settings_to_update = [
        'blog_title'       => $_POST['blog_title'] ?? 'My Blog',
        'blog_description' => $_POST['blog_description'] ?? '',
        'posts_per_page'   => (int)($_POST['posts_per_page'] ?? 10),
        'pagination_style' => $_POST['pagination_style'] ?? 'numbered',
        'show_back_to_top' => isset($_POST['show_back_to_top']) ? 'true' : 'false',
        'footer_text'      => $_POST['footer_text'] ?? '&copy; ' . date('Y') . ' My Blog',
    ];

    // Loop through and update/add each setting
    foreach ($settings_to_update as $key => $value) {
        // Format value for insertion into the PHP file
        $formatted_value = is_bool($value) || is_numeric($value) ? $value : "'" . addslashes($value) . "'";

        // Pattern to find an existing key
        $pattern = "/'{$key}'\s*=>\s*(?:'.*?'|\d+|true|false)/";

        // Check if the key exists
        if (preg_match($pattern, $config_content)) {
            // If it exists, replace it
            $replacement = "'{$key}' => " . $formatted_value;
            $config_content = preg_replace($pattern, $replacement, $config_content, 1);
        } else {
            // If it doesn't exist, add it before the closing parenthesis of the array
            $new_setting_line = "\n  '{$key}' => {$formatted_value},";
            // Find the last closing parenthesis
            $last_paren_pos = strrpos($config_content, ')');
            if ($last_paren_pos !== false) {
                 $config_content = substr_replace($config_content, $new_setting_line, $last_paren_pos, 0);
            }
        }
    }

    // Write the updated content back to the file
    if (file_put_contents($config_file, $config_content)) {
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($config_file, true);
        }
        header('Location: settings.php?success=Settings saved successfully!');
        exit;
    } else {
        $error = "Failed to save settings. Please check that `config.php` is writable.";
    }
}

// --- Display Page Content ---
$config = require $config_file;

// Set defaults for new settings if they don't exist
$posts_per_page = $config['posts_per_page'] ?? 10;
$pagination_style = $config['pagination_style'] ?? 'numbered';
$show_back_to_top = $config['show_back_to_top'] ?? false;
$footer_text = $config['footer_text'] ?? '&copy; ' . date('Y') . ' ' . htmlspecialchars($config['blog_title']);
?>

<div class="page-header">
    <h1>General Settings</h1>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="card" style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px;">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="card" style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px;">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="card">
    <form action="settings.php" method="post">
        <fieldset>
            <legend>Basic Information</legend>
            <div class="form-group">
                <label for="blog_title">Site Title</label>
                <input type="text" id="blog_title" name="blog_title" value="<?php echo htmlspecialchars($config['blog_title']); ?>">
            </div>
            <div class="form-group">
                <label for="blog_description">Site Tagline</label>
                <input type="text" id="blog_description" name="blog_description" value="<?php echo htmlspecialchars($config['blog_description']); ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Reading Settings</legend>
            <div class="form-group">
                <label for="posts_per_page">Posts Per Page</label>
                <input type="number" id="posts_per_page" name="posts_per_page" value="<?php echo $posts_per_page; ?>" min="1" max="100">
            </div>
            <div class="form-group">
                <label for="pagination_style">Pagination Style</label>
                <select id="pagination_style" name="pagination_style">
                    <option value="numbered" <?php if ($pagination_style === 'numbered') echo 'selected'; ?>>Numbered Links</option>
                    <option value="load_more" <?php if ($pagination_style === 'load_more') echo 'selected'; ?>>"Load More" Button</option>
                </select>
            </div>
        </fieldset>

        <fieldset>
            <legend>Footer Settings</legend>
             <div class="form-group">
                <label for="footer_text">Footer Text</label>
                <input type="text" id="footer_text" name="footer_text" value="<?php echo htmlspecialchars($footer_text); ?>">
            </div>
            <div class="form-group">
                <label for="show_back_to_top">
                    <input type="checkbox" id="show_back_to_top" name="show_back_to_top" role="switch" <?php if ($show_back_to_top) echo 'checked'; ?>>
                    Show "Back to Top" Link
                </label>
            </div>
        </fieldset>

        <button type="submit" class="btn">Save Settings</button>
    </form>
</div>

<?php include 'partials/footer.php'; ?>