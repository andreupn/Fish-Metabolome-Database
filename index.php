<?php
// Ensure session is started if not done in config.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "_config/config.php";

// Function to handle URL base (assuming base_url() is defined in config.php or elsewhere)
if (!function_exists('base_url')) {
    /**
     * Constructs a base URL path, using a predefined constant for the root path
     * if available, or calculating it based on server variables.
     */
    function base_url($path = '') {
        // Use a defined constant for the app root if you set one in config.php
        if (defined('APP_ROOT_URL')) {
            return APP_ROOT_URL . $path;
        }

        // Fallback: Calculate base URL dynamically
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $host = $_SERVER['HTTP_HOST'];
        
        // Calculate the directory of the application root (assuming this file is at the root of the app)
        $doc_root = str_replace(['\\', '/'], '/', $_SERVER['DOCUMENT_ROOT']);
        $script_filename = str_replace(['\\', '/'], '/', $_SERVER['SCRIPT_FILENAME']);
        
        // Calculate relative path from DOCUMENT_ROOT to the script's directory
        $app_dir = trim(str_replace($doc_root, '', dirname($script_filename)), '/');
        
        $base = $protocol . '://' . $host;
        
        if (!empty($app_dir)) {
            $base .= '/' . $app_dir;
        }
        
        // Ensure only one slash separates the base and the path
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}


if(isset($_SESSION['login'])) {
    // ✅ Secure server-side redirection for logged-in users
    header("Location: " . base_url('dashboard'));
    exit;
} else {
    // ✅ Secure server-side redirection for unauthenticated users
    header("Location: " . base_url('home/index.php'));
    exit;
}