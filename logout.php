<?php
session_start(); // Start session if not already started

// Regenerate session ID for security
session_regenerate_id(true);

// Unset all session variables
$_SESSION = [];

// Destroy the session completely
session_destroy();

// Remove session cookie (for extra security)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login page
header("Location: blood_bank.php");
exit();
?>
