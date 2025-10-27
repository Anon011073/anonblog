# Minimalist PHP Blog

A lightweight, file-based blog engine that uses Markdown for posts and a simple theme and plugin system.

## Features

*   **Markdown-Based**: Write blog posts in simple Markdown format.
*   **File-Based**: No database required. All content is stored in files.
*   **Theme Support**: Easily change the look and feel of your blog with themes.
*   **Plugin System**: Extend the functionality of your blog with plugins.
*   **Widget System**: Add widgets to your theme's sidebar.
*   **Admin Panel**: A simple admin panel to manage your blog.
*   **Responsive Default Theme**: The default theme is built with Pico CSS and is responsive out of the box.

## Requirements

*   PHP 7.4 or higher
*   Composer

## Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/php-minimalist-blog.git
    cd php-minimalist-blog
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Configure the blog:**
    - Rename `config.php.example` to `config.php`.
    - Edit `config.php` to set your blog's title, description, and other settings.

4.  **Set up the posts directory:**
    - The `posts_dir` in `config.php` should be set to the absolute path of your posts directory.
    - For example, `/var/www/html/my-blog/posts`. Make sure this directory exists and is writable by the web server.

5.  **Start the local server:**
    ```bash
    php -S localhost:8080 -t . router.php
    ```
    Your blog will be available at `http://localhost:8080`.

## Configuration

The main configuration file is `config.php`. Here are the key options:

*   `blog_title`: The title of your blog.
*   `blog_description`: A short description of your blog.
*   `base_path`: The subdirectory of your blog if it's not in the root. For example, if your blog is at `http://example.com/blog`, set this to `/blog`.
*   `active_theme`: The name of the theme to use.
*   `posts_dir`: The directory where your blog posts are stored.
*   `admin_password`: The password for the admin panel.
*   `enabled_plugins`: An array of plugin filenames to enable.
*   `posts_per_page`: The number of posts to show on each page.
*   `pagination_style`: The style of the pagination (`numbered` or `next_previous`).

## Themes

Themes are located in the `themes` directory. Each theme is a separate subdirectory and can have its own `functions.php`, templates, and assets.

To switch themes, change the `active_theme` value in `config.php`.

The project comes with two themes:
*   `default`: A polished theme using Pico CSS.
*   `barebones`: A minimal theme for developers.

## Plugins and Widgets

### Plugins

Plugins are located in the `plugins` directory. To enable a plugin, add its filename to the `enabled_plugins` array in `config.php`.

### Widgets

Widgets are defined in `src/widgets.php` and individual widget files are in the `/widgets` directory. You can register and display widgets in your theme.

## Admin Panel

The admin panel is located at `/admin`. You can log in with the password you set in `config.php`. The admin panel allows you to:

*   View, create, edit, and delete posts.
*   Manage settings.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.
