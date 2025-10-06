<?php

// src/core.php

use Parsedown;

/**
 * Parses a post file to extract metadata and its Markdown content.
 * This function does not depend on configuration.
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
    $parts = preg_split('/\R---\R/', $content, 2);
    $metadata = [];
    $markdown_content = $parts[1] ?? '';

    if (isset($parts[0])) {
        $lines = explode("\n", $parts[0]);
        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $metadata[strtolower(trim($key))] = trim($value);
            }
        }
    }

    $parsedown = new Parsedown();
    $html_content = $parsedown->text($markdown_content);
    $html_content = apply_filters('post_content', $html_content);

    return [
        'slug' => basename($filepath, '.md'),
        'title' => $metadata['title'] ?? 'Untitled Post',
        'category' => $metadata['category'] ?? 'Uncategorized',
        'featured_image' => $metadata['featured_image'] ?? '',
        'timestamp' => isset($metadata['date']) ? strtotime($metadata['date']) : filemtime($filepath),
        'content' => $html_content,
        'raw_content' => $markdown_content,
    ];
}

/**
 * Retrieves a single post by its URL slug.
 *
 * @param array $config The application configuration array.
 * @param string $slug The slug of the post.
 * @return array|null The post data array or null if not found.
 */
function get_post(array $config, string $slug): ?array
{
    $filepath = $config['posts_dir'] . '/' . htmlspecialchars($slug) . '.md';
    return parse_post_file($filepath);
}

/**
 * Retrieves all posts, sorted by date in descending order.
 *
 * @param array $config The application configuration array.
 * @return array An array of all posts.
 */
function get_all_posts(array $config): array
{
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

    usort($posts, function ($a, $b) {
        return $b['timestamp'] <=> $a['timestamp'];
    });

    return $posts;
}

/**
 * Renders a view using the active theme.
 *
 * @param array $config The application configuration array.
 * @param string $template_name The name of the template file (e.g., 'home', 'post').
 * @param array $data The data to be extracted into variables for the template.
 */
function render(array $config, string $template_name, array $data = []): void
{
    $theme_dir_name = $config['active_theme'] ?? 'default';
    $theme_path = ROOT_PATH . '/themes/' . $theme_dir_name;

    // Pass the config and theme URL to the template
    $data['config'] = $config;
    // Prepend '../' to make the path relative to the /public/ directory
    $data['theme_url'] = '../themes/' . $theme_dir_name;

    extract($data);

    ob_start();
    $template_file = $theme_path . '/' . $template_name . '.php';
    if (file_exists($template_file)) {
        include $template_file;
    } else {
        echo "Error: Template '$template_name' not found in theme '{$theme_dir_name}'.";
    }
    $content = ob_get_clean();

    include $theme_path . '/layout.php';
}
