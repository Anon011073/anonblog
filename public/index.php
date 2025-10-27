<?php
define('ROOT_PATH', dirname(__DIR__));
$config = require ROOT_PATH . '/config.php';

// Main entry point for the theme system
require_once ROOT_PATH . '/src/functions.php';

// Load the plugin system and all enabled plugins
if (file_exists(ROOT_PATH . '/src/plugins.php')) {
    require_once ROOT_PATH . '/src/plugins.php';
    load_plugins($config);
}

// Load the active theme
load_theme();

// Handle the request
get_header();
handle_request();
get_footer();
