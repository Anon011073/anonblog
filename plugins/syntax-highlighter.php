<?php

// plugins/syntax-highlighter.php

/**
 * Plugin Name: Syntax Highlighter
 * Description: Adds syntax highlighting to code blocks using Prism.js from a CDN.
 * Version: 1.1
 */

// Don't allow direct access to this file.
if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 * Adds the Prism.js CSS file to the <head> of the theme.
 * The SRI integrity checks have been removed to prevent issues with CDN file updates.
 */
function sh_add_prism_css()
{
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-okaidia.min.css">';
}

/**
 * Adds the Prism.js JavaScript files to the footer of the theme.
 * - prism-core.min.js is the main library.
 * - prism-autoloader.min.js automatically loads languages as needed.
 */
function sh_add_prism_js()
{
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>';
}

// Register the functions with the plugin hooks.
add_hook('head_tags', 'sh_add_prism_css');
add_hook('footer_scripts', 'sh_add_prism_js');
