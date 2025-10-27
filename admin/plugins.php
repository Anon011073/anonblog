<?php
// admin/plugins.php
include 'partials/header.php';

$config_file = __DIR__ . '/../config.php';

// --- Handle Plugin Activation/Deactivation ---
if (isset($_GET['action']) && isset($_GET['plugin'])) {
    $config = require $config_file;
    $plugin_file = basename($_GET['plugin']);
    $enabled_plugins = $config['enabled_plugins'] ?? [];

    if ($_GET['action'] === 'activate') {
        // Add the plugin if it's not already in the array
        if (!in_array($plugin_file, $enabled_plugins)) {
            $enabled_plugins[] = $plugin_file;
        }
    } elseif ($_GET['action'] === 'deactivate') {
        // Remove the plugin from the array
        $enabled_plugins = array_filter($enabled_plugins, function($p) use ($plugin_file) {
            return $p !== $plugin_file;
        });
    }

    // Update the config array and save the file
    $config['enabled_plugins'] = array_values($enabled_plugins); // Re-index the array
    $new_config_content = "<?php\n\n// Blog configuration\nreturn " . var_export($config, true) . ";\n";
    if (file_put_contents($config_file, $new_config_content)) {
        header('Location: plugins.php?success=Plugin settings updated!');
        exit;
    } else {
        $error = "Failed to update config file. Please check permissions.";
    }
}

// --- Display Page Content ---
$config = require $config_file;
$enabled_plugins = $config['enabled_plugins'] ?? [];
$plugins_dir = __DIR__ . '/../plugins';

// Scan for all .php files in the plugins directory
$all_plugins_files = array_filter(scandir($plugins_dir), function($item) {
    return pathinfo($item, PATHINFO_EXTENSION) === 'php';
});

/**
 * Parses the docblock of a plugin file to get its metadata.
 * @param string $plugin_file The filename of the plugin.
 * @return array An array containing the plugin's name and description.
 */
function get_plugin_info($plugin_file) {
    $plugin_path = __DIR__ . '/../plugins/' . $plugin_file;
    $file_content = file_get_contents($plugin_path);

    // Default values
    $info = ['name' => $plugin_file, 'description' => 'No description available.'];

    // Parse Plugin Name
    if (preg_match('/Plugin Name:(.*)/', $file_content, $matches)) {
        $info['name'] = trim($matches[1]);
    }
    // Parse Description
    if (preg_match('/Description:(.*)/', $file_content, $matches)) {
        $info['description'] = trim($matches[1]);
    }
    return $info;
}
?>

<div class="page-header">
    <h1>Plugins</h1>
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
    <h2>Manage Plugins</h2>
    <p>Activate or deactivate plugins to add or remove functionality from your site.</p>
    <table class="posts-table">
        <thead>
            <tr>
                <th style="width: 30%;">Plugin</th>
                <th>Description</th>
                <th style="width: 20%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_plugins_files as $plugin_file):
                $info = get_plugin_info($plugin_file);
                $is_active = in_array($plugin_file, $enabled_plugins);
            ?>
                <tr class="<?php if ($is_active) echo 'active-plugin'; ?>">
                    <td><strong><?php echo htmlspecialchars($info['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($info['description']); ?></td>
                    <td>
                        <?php if ($is_active): ?>
                            <a href="plugins.php?action=deactivate&plugin=<?php echo urlencode($plugin_file); ?>" class="btn-delete">Deactivate</a>
                        <?php else: ?>
                            <a href="plugins.php?action=activate&plugin=<?php echo urlencode($plugin_file); ?>" class="btn">Activate</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<style>
.active-plugin { background-color: #eff6ff; }
</style>

<?php include 'partials/footer.php'; ?>
