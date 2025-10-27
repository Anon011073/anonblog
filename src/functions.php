<?php
// Core theme functions

// Include the Composer autoloader
require_once ROOT_PATH . '/vendor/autoload.php';

// Include widget and auth functions
require_once ROOT_PATH . '/src/widgets.php';
require_once ROOT_PATH . '/admin/auth.php';


// The hook system is defined in /src/plugins.php

function get_posts() {
    $config = require ROOT_PATH . '/config.php';
    $posts_dir = $config['posts_dir'];
    $files = glob($posts_dir . '/*.md');
    $posts = [];

    $Parsedown = new Parsedown();

    foreach ($files as $file) {
        $content = file_get_contents($file);
        $parts = preg_split('/---\\R/', $content, 2);

        $post = [];
        $post['raw_content'] = $parts[1];
        $post['content'] = $Parsedown->text($parts[1]);
        $post['slug'] = basename($file, '.md');

        $meta_lines = explode("\n", $parts[0]);
        foreach ($meta_lines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $key = strtolower(str_replace(' ', '_', trim($key)));
                $post[$key] = trim($value);
            }
        }

        if (isset($post['date'])) {
            $post['timestamp'] = strtotime($post['date']);
        } else {
            $post['timestamp'] = filemtime($file);
        }

        $posts[] = $post;
    }

    usort($posts, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });

    return $posts;
}


function get_active_theme() {
    $config = require ROOT_PATH . '/config.php';
    return $config['active_theme'] ?? 'default';
}

function load_theme() {
    $theme = get_active_theme();
    $theme_dir = ROOT_PATH . '/themes/' . $theme;

    if (file_exists($theme_dir . '/functions.php')) {
        require_once $theme_dir . '/functions.php';
    }
}

function get_template_part($slug, $name = null) {
    global $posts, $pagination, $post, $config;
    $theme = get_active_theme();
    $template = $name ? "{$slug}-{$name}.php" : "{$slug}.php";
    $path = ROOT_PATH . '/themes/' . $theme . '/' . $template;

    if (file_exists($path)) {
        include $path;
    }
}

function get_header() {
    get_template_part('header');
}

function get_footer() {
    get_template_part('footer');
}

function get_theme_file_uri($file) {
    $config = require ROOT_PATH . '/config.php';
    $base_path = $config['base_path'] ?? '';
    $theme = get_active_theme();
    return $base_path . "/themes/{$theme}/" . ltrim($file, '/');
}

function get_post($slug) {
    $config = require ROOT_PATH . '/config.php';
    $posts_dir = $config['posts_dir'];
    $filepath = $posts_dir . '/' . $slug . '.md';

    if (!file_exists($filepath)) {
        return null;
    }

    $Parsedown = new Parsedown();
    $content = file_get_contents($filepath);
    $parts = preg_split('/---\\R/', $content, 2);

    $post = [];
    $post['raw_content'] = $parts[1];
    $post['content'] = $Parsedown->text($parts[1]);
    $post['slug'] = $slug;

    $meta_lines = explode("\n", $parts[0]);
    foreach ($meta_lines as $line) {
        if (strpos($line, ':') !== false) {
            list($key, $value) = explode(':', $line, 2);
            $key = strtolower(str_replace(' ', '_', trim($key)));
            $post[$key] = trim($value);
        }
    }

    if (isset($post['date'])) {
        $post['timestamp'] = strtotime($post['date']);
    } else {
        $post['timestamp'] = filemtime($filepath);
    }

    return $post;
}

function handle_request() {
    global $config;
    if (isset($_GET['post'])) {
        $slug = basename($_GET['post']);
        $GLOBALS['post'] = get_post($slug);
        if ($GLOBALS['post']) {
            get_template_part('post');
        } else {
            header("HTTP/1.0 404 Not Found");
            get_template_part('404');
        }
    } else {
        $all_posts = get_posts();
        $posts_per_page = $config['posts_per_page'] ?? 5;
        $current_page = isset($_GET['paged']) ? (int)$_GET['paged'] : 1;
        $total_posts = count($all_posts);
        $total_pages = ceil($total_posts / $posts_per_page);

        $offset = ($current_page - 1) * $posts_per_page;
        $GLOBALS['posts'] = array_slice($all_posts, $offset, $posts_per_page);

        $GLOBALS['pagination'] = [
            'total_pages' => $total_pages,
            'current_page' => $current_page,
            'style' => $config['pagination_style'] ?? 'numbered',
        ];

        get_template_part('index');
    }
}
