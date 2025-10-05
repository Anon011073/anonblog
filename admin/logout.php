<?php
// admin/logout.php

/**
 * This script handles the logout process.
 * It destroys the user's session and redirects them to the login page.
 */

// 1. Start the session to gain access to it.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Unset all of the session variables.
$_SESSION = [];

// 3. If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finally, destroy the session.
session_destroy();

// 5. Redirect to the login page.
header("Location: index.php");
exit;
