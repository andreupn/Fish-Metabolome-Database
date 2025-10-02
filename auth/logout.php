<?php
// Ensure session is started if not done in config.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "../_config/config.php";

// 1. Clear all session variables
$_SESSION = array();

// 2. Destroy the session cookie/data
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// 3. Securely redirect to the login page
// ✅ SECURITY FIX: Use server-side header redirection (Preferred)
header("Location: " . base_url('auth/login.php'));
exit; 
?>