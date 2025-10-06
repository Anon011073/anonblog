<?php
// admin/themes.php
include 'partials/header.php';

$config_file = __DIR__ . '/../config.php';

// --- Handle Theme Activation ---
if (isset($_GET['activate'])) {
    // Sanitize the input to ensure it's a valid directory name
    $new_theme = basename($_GET['activate']);

    // Read the raw content of the config file
    $config_content = file_get_contents($config_file);

    // Use a regular expression to replace only the active_theme value.
    // This is safer than var_export as it preserves the original file structure,
    // including the relative path for posts_dir.
    $pattern = "/'active_theme'\\s*=>\\s*'.*?'/";
    $replacement = "'active_theme' => '{$new_theme}'";
    $new_config_content = preg_replace($pattern, $replacement, $config_content, 1);

    if ($new_config_content !== null && $new_config_content !== $config_content) {
        if (file_put_contents($config_file, $new_config_content)) {
            // Clear the opcache for the config file to ensure the change is reflected immediately
            if (function_exists('opcache_invalidate')) {
                opcache_invalidate($config_file, true);
            }
            header('Location: themes.php?success=Theme activated successfully!');
            exit;
        } else {
            $error = "Failed to write to config file. Please check permissions.";
        }
    } else {
        $error = "Failed to update the theme in the config file. The 'active_theme' setting may be missing or malformed.";
    }
}

// --- Display Page Content ---
$config = require $config_file;
$active_theme = $config['active_theme'] ?? 'default';

// Scan the themes directory to find all available themes
$themes_dir = __DIR__ . '/../themes';
$themes = array_filter(scandir($themes_dir), function($item) use ($themes_dir) {
    return is_dir($themes_dir . '/' . $item) && !in_array($item, ['.', '..']);
});
?>

<div class="page-header">
    <h1>Appearance</h1>
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
    <h2>Themes</h2>
    <p>Select a theme to change the appearance of your public-facing site.</p>
    <div class="themes-grid">
        <?php foreach ($themes as $theme):
            $preview_style = '';
            if ($theme === 'default') {
                $preview_style = 'background-color: #f9f9f9; color: #333;';
            } elseif ($theme === 'dark') {
                $preview_style = 'background-color: #333; color: #f9f9f9;';
            }
        ?>
            <div class="theme-card <?php if ($theme === $active_theme) echo 'active'; ?>">
                <div class="theme-info" style="<?php echo $preview_style; ?>">
                    <h3><?php echo htmlspecialchars(ucfirst($theme)); ?></h3>
                    <p style="opacity: 0.7;">A preview of the theme.</p>
                </div>
                <div class="theme-actions">
                    <?php if ($theme === $active_theme): ?>
                        <span class="btn-active">Currently Active</span>
                    <?php else: ?>
                        <a href="themes.php?activate=<?php echo urlencode($theme); ?>" class="btn">Activate</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
// Add some specific styles for this page
// A real app might put this in admin.css, but this is fine for a single page.
?>
<style>
.themes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.theme-card {
    border: 2px solid #e7e7e7;
    border-radius: 5px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.theme-card.active {
    border-color: #3b82f6;
}
.theme-info {
    padding: 20px;
}
.theme-info h3 {
    margin-top: 0;
}
.theme-actions {
    background-color: #f8f9fa;
    padding: 15px 20px;
    text-align: right;
    border-top: 1px solid #e7e7e7;
}
.btn-active {
    font-weight: bold;
    color: #555;
}
</style>

<?php include 'partials/footer.php'; ?>
