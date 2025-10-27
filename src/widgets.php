<?php
// src/widgets.php

/**
 * Scans the widgets directory and returns an array of available widgets.
 * Each widget is a PHP file that should return an array with 'name', 'description', and a 'render' callable.
 *
 * @return array Available widgets.
 */
function get_available_widgets(): array
{
    $widgets_dir = ROOT_PATH . '/widgets';
    $available_widgets = [];

    if (!is_dir($widgets_dir)) {
        // Create the directory if it doesn't exist to prevent errors.
        mkdir($widgets_dir, 0755, true);
        return [];
    }

    $files = scandir($widgets_dir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $widget_id = pathinfo($file, PATHINFO_FILENAME);
            $widget_data = include $widgets_dir . '/' . $file;
            if (is_array($widget_data) && isset($widget_data['name']) && is_callable($widget_data['render'])) {
                $available_widgets[$widget_id] = $widget_data;
            }
        }
    }
    return $available_widgets;
}

/**
 * Renders a specific widget area (e.g., 'sidebar').
 *
 * @param string $widget_area The name of the widget area to render.
 */
function render_widget_area(string $widget_area): void
{
    $config_file = ROOT_PATH . '/data/widgets.json';
    if (!file_exists($config_file)) {
        return;
    }

    $widget_config = json_decode(file_get_contents($config_file), true);
    if (!isset($widget_config[$widget_area]) || empty($widget_config[$widget_area])) {
        return;
    }

    $active_widgets = $widget_config[$widget_area];
    $available_widgets = get_available_widgets();

    echo '<div class="widget-area widget-area-' . htmlspecialchars($widget_area) . '">';
    foreach ($active_widgets as $widget_id) {
        if (isset($available_widgets[$widget_id])) {
            echo '<div class="widget widget-' . htmlspecialchars($widget_id) . '">';
            // You might want to add a title here in a real implementation
            // echo '<h3 class="widget-title">' . htmlspecialchars($available_widgets[$widget_id]['name']) . '</h3>';
            call_user_func($available_widgets[$widget_id]['render']);
            echo '</div>';
        }
    }
    echo '</div>';
}

/**
 * Checks if a given widget area has any active widgets.
 *
 * @param string $widget_area The name of the widget area.
 * @return bool True if the area has active widgets, false otherwise.
 */
function is_widget_area_active(string $widget_area): bool
{
    $config_file = ROOT_PATH . '/data/widgets.json';
    if (!file_exists($config_file)) {
        return false;
    }

    $widget_config = json_decode(file_get_contents($config_file), true);

    return isset($widget_config[$widget_area]) && !empty($widget_config[$widget_area]);
}