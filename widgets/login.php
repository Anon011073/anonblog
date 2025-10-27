<?php
// widgets/login.php

return [
    'name' => 'Login / Admin',
    'description' => 'Displays a "Login" link, or an "Admin" link if logged in.',
    'render' => function() {
        if (function_exists('is_logged_in') && is_logged_in()) {
            echo '<a href="../admin/dashboard.php">Admin</a>';
        } else {
            echo '<a href="../admin/index.php">Login</a>';
        }
    }
];