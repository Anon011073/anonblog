<?php

// src/core.php

use Parsedown;

/**
 * Parses a post file to extract metadata and its Markdown content.
 * This is the heart of the flat-file system.
 *
 * @param string $filepath The full path to the post file.
 * @return array|null An array with post data or null if the file doesn't exist.
 */
function parse_post_file(string $filepath): ?array
{
    if (!file_exists($filepath)) {
        return null;
    }

    $content = file_get_contents($filepath);

    // Separate metadata (front-matter) from the actual content
    $parts = preg_split('/\R---\R/', $content, 2);

    $metadata = [];
    $markdown_content = $parts[1] ?? '';

    // Parse the metadata block
    if (isset($parts[0])) {
        $lines = explode("\n", $parts[0]);
        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $metadata[strtolower(trim($key))] = trim($value);
            }
        }
    }

    // Use Parsedown to convert Markdown to HTML
    $parsedown = new Parsedown();
    $html_content = $parsedown->text($markdown_content);

    return [
        'slug' => basename($filepath, '.md'),
        'title' => $metadata['title'] ?? 'Untitled Post',
        'timestamp' => isset($metadata['date']) ? strtotime($metadata['date']) : filemtime($filepath),
        'content' => $html_content, // The rendered HTML content
        'raw_content' => $markdown_content, // The original Markdown for excerpts
    ];
}

/**
 * Retrieves a single post by its URL slug.
 *
 * @param string $slug The slug of the post.
 * @return array|null The post data array or null if not found.
 */
function get_post(string $slug): ?array
{
    global $config;
    $filepath = $config['posts_dir'] . '/' . htmlspecialchars($slug) . '.md';
    return parse_post_file($filepath);
}

/**
 * Retrieves all posts, sorted by date in descending order.
 *
 * @return array An array of all posts.
 */
function get_all_posts(): array
{
    global $config;
    $posts_dir = $config['posts_dir'];
    $files = scandir($posts_dir);
    $posts = [];

    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'md') {
            $filepath = $posts_dir . '/' . $file;
            $post = parse_post_file($filepath);
            if ($post) {
                $posts[] = $post;
            }
        }
    }

    // Sort posts by timestamp, newest first
    usort($posts, function ($a, $b) {
        return $b['timestamp'] <=> $a['timestamp'];
    });

    return $posts;
}

/**
 * Renders a view using the active theme.
 * It captures the output of a template and injects it into the main layout.
 *
 * @param string $template_name The name of the template file (e.g., 'home', 'post').
 * @param array $data The data to be extracted into variables for the template.
 */
function render(string $template_name, array $data = []): void
{
    global $config;
    $theme_path = ROOT_PATH . '/themes/' . $config['active_theme'];

    // Make data from the controller available as variables in the template
    extract($data);

    // Start output buffering to capture the template's HTML
    ob_start();

    $template_file = $theme_path . '/' . $template_name . '.php';
    if (file_exists($template_file)) {
        include $template_file;
    } else {
        // Fallback for missing templates
        echo "Error: Template '$template_name' not found in theme '{$config['active_theme']}'.";
    }

    // Get the captured content and clean the buffer
    $content = ob_get_clean();

    // Include the main layout file, which will now have access to $content
    include $theme_path . '/layout.php';
}
