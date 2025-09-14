<?php
// admin/themes.php
include 'partials/header.php';

$config_file = __DIR__ . '/../config.php';

// --- Handle Theme Activation ---
if (isset($_GET['activate'])) {
    // Sanitize the input to ensure it's a valid directory name
    $new_theme = basename($_GET['activate']);

    // Read the current config
    $config = require $config_file;
    // Update the active theme value
    $config['active_theme'] = $new_theme;

    // Write the updated config back to the file
    // Using var_export is a safe way to generate PHP code from an array
    $new_config_content = "<?php\n\n// Blog configuration\nreturn " . var_export($config, true) . ";\n";
    if (file_put_contents($config_file, $new_config_content)) {
        // Redirect with a success message
        header('Location: themes.php?success=Theme activated successfully!');
        exit;
    } else {
        $error = "Failed to update config file. Please check permissions.";
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
        <?php foreach ($themes as $theme): ?>
            <div class="theme-card <?php if ($theme === $active_theme) echo 'active'; ?>">
                <div class="theme-info">
                    <h3><?php echo htmlspecialchars(ucfirst($theme)); ?></h3>
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
