<?php
// admin/auth.php

/**
 * This file provides authentication helper functions for the admin area.
 * It should be included at the top of any protected admin page.
 */

// Ensure the session is started. It's safe to call this multiple times.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Checks if the current user is authenticated.
 *
 * @return bool True if the user is logged in, false otherwise.
 */
function is_logged_in(): bool
{
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

/**
 * Enforces authentication for a page.
 * If the user is not logged in, they are redirected to the login page
 * and the script execution is terminated.
 */
function require_login(): void
{
    if (!is_logged_in()) {
        // Redirect to the login page
        header('Location: index.php?error=You must be logged in to view this page.');
        exit; // Stop script execution immediately
    }
}
