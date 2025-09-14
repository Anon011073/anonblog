<?php

// src/plugins.php

/**
 * A simple, hook-based plugin system for the blog.
 */
class PluginSystem
{
    /**
     * @var array Stores all registered hooks and their callbacks.
     */
    private static $hooks = [];

    /**
     * Registers a callback function to a specific hook.
     *
     * @param string $hook_name The name of the hook (e.g., 'head_tags').
     * @param callable $callback The function to execute when the hook is called.
     * @param int $priority The execution order for the callback (lower numbers run first).
     */
    public static function add_hook(string $hook_name, callable $callback, int $priority = 10)
    {
        // Initialize the priority array if it doesn't exist
        if (!isset(self::$hooks[$hook_name][$priority])) {
            self::$hooks[$hook_name][$priority] = [];
        }
        self::$hooks[$hook_name][$priority][] = $callback;
    }

    /**
     * Executes all registered callbacks for a given hook.
     * This is used for "actions" that add output (e.g., adding a <link> tag).
     *
     * @param string $hook_name The name of the hook to execute.
     * @param mixed ...$args Arguments to pass to the callback functions.
     */
    public static function do_hook(string $hook_name, ...$args)
    {
        if (isset(self::$hooks[$hook_name])) {
            ksort(self::$hooks[$hook_name]); // Sort by priority
            foreach (self::$hooks[$hook_name] as $priority_group) {
                foreach ($priority_group as $callback) {
                    call_user_func_array($callback, $args);
                }
            }
        }
    }

    /**
     * Executes all registered callbacks for a hook and filters a value.
     * This is used for "filters" that modify data (e.g., changing post content).
     *
     * @param string $hook_name The name of the hook to execute.
     * @param mixed $value The initial value to be filtered.
     * @param mixed ...$args Additional arguments to pass to the callback functions.
     * @return mixed The filtered value.
     */
    public static function apply_filters(string $hook_name, $value, ...$args)
    {
        if (isset(self::$hooks[$hook_name])) {
            ksort(self::$hooks[$hook_name]); // Sort by priority
            foreach (self::$hooks[$hook_name] as $priority_group) {
                foreach ($priority_group as $callback) {
                    // Prepend the value to the arguments array for the callback
                    $value = call_user_func_array($callback, array_merge([$value], $args));
                }
            }
        }
        return $value;
    }
}

// --- Procedural Wrappers for easy use in plugins ---

function add_hook(string $hook_name, callable $callback, int $priority = 10)
{
    PluginSystem::add_hook($hook_name, $callback, $priority);
}

function do_hook(string $hook_name, ...$args)
{
    PluginSystem::do_hook($hook_name, ...$args);
}

function apply_filters(string $hook_name, $value, ...$args)
{
    return PluginSystem::apply_filters($hook_name, $value, ...$args);
}

/**
 * Loads all enabled plugins from the configuration.
 * @param array $config The application configuration array.
 */
function load_plugins(array $config)
{
    $enabled_plugins = $config['enabled_plugins'] ?? [];
    foreach ($enabled_plugins as $plugin_file) {
        $plugin_path = ROOT_PATH . '/plugins/' . $plugin_file;
        if (file_exists($plugin_path)) {
            require_once $plugin_path;
        }
    }
}
