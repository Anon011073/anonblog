<?php
// admin/login.php

// This script handles the login form submission.

// Always start the session on pages that deal with authentication.
session_start();

// Load the main configuration file to get the admin password.
// Using __DIR__ ensures the path is correct regardless of where this script is called from.
$config = require_once __DIR__ . '/../config.php';

// --- Redirect if already logged in ---
// If the user is already logged in, send them to the dashboard.
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: dashboard.php');
    exit;
}

// --- Process POST request ---
// Only process login attempts if the form was submitted via POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    // Verify the submitted password against the one in the config file.
    if (!empty($password) && $password === $config['admin_password']) {
        // Password is correct.
        // Set a session variable to mark the user as logged in.
        $_SESSION['loggedin'] = true;

        // Regenerate the session ID to prevent session fixation attacks.
        session_regenerate_id(true);

        // Redirect to the protected admin dashboard.
        header('Location: dashboard.php');
        exit;
    } else {
        // Incorrect password. Redirect back to the login page with an error message.
        header('Location: index.php?error=Invalid password. Please try again.');
        exit;
    }
} else {
    // --- Redirect non-POST requests ---
    // If the script is accessed directly via GET, just redirect to the login page.
    header('Location: index.php');
    exit;
}
