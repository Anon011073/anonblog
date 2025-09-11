<?php

// plugins/syntax-highlighter.php

/**
 * Plugin Name: Syntax Highlighter
 * Description: Adds syntax highlighting to code blocks using Prism.js from a CDN.
 * Version: 1.0
 */

// Don't allow direct access to this file.
if (!defined('ROOT_PATH')) {
    exit('No direct script access allowed');
}

/**
 * Adds the Prism.js CSS file to the <head> of the theme.
 * I've chosen the "Okaidia" theme for a nice dark look.
 */
function sh_add_prism_css()
{
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-okaidia.min.css" integrity="sha512-mIs9kKbaw6JutbgfZpqDDAdocjdaxpWDBOPgBqzCvQZTBKOfUSjs9ibDfcOcuItcNrJ3Muzr1G/wFMDSoiIEcw==" crossorigin="anonymous" referrerpolicy="no-referrer" />';
}

/**
 * Adds the Prism.js JavaScript files to the footer of the theme.
 * - prism-core.min.js is the main library.
 * - prism-autoloader.min.js automatically loads languages as needed.
 */
function sh_add_prism_js()
{
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js" integrity="sha512-9khQRAUBYEJDCDVP2yw3LRUQvjJ0Pjx0EShmaQjcHa6AXiOv6qHQu9lCAIR8O+/D8FtaCoJ2c0Tf9Xo7hYH01Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js" integrity="sha512-zc7MmmNdmfsrB6WoES13efM9IAiPHhGOGPc+NovGGsRDOaDuJqLpfJezPItctskjZyNAn4GUAMU_JobbzR+HDg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>';
}

// Register the functions with the plugin hooks.
add_hook('head_tags', 'sh_add_prism_css');
add_hook('footer_scripts', 'sh_add_prism_js');
