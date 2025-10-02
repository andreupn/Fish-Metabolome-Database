<?php
// Ensure this file is used only for configuration and secure connection setup.
// Most logic is moved into secure functions.

// setting default TimeZone
date_default_timezone_set('Asia/Jakarta');

// --- CRITICAL SECURITY: Use session_start() only once, usually in _header.php ---
// session_start(); 
// We will assume session_start() is handled in the application's entry point/header.

// koneksi ke databse
// Using object-oriented mysqli for better error handling and compatibility with prepared statements
$conn = new mysqli("localhost", "root", "", "fmdb");

if( $conn->connect_errno ) {
    // SECURITY FIX: Do not echo the error number/details publicly in a production environment
    error_log("Failed to connect to MySQL: " . $conn->connect_error);
    die("Database connection error."); 
}

// -------------------------------------------------------------
// --- Helper Functions ---
// -------------------------------------------------------------

//fungsi base_url (Kept simple, assuming base_url is fixed for security)
function base_url($url = null) {
    // Note: If deploying to a live server, this must be changed to the correct URL/protocol.
    $base_url = "http://localhost/fmdbbootstrap"; 
    
    if($url !== null) {
        // SECURITY NOTE: We don't use secure_output here as base_url is for internal linking.
        // XSS defense is handled where base_url is output to HTML.
        return rtrim($base_url, '/') . '/' . ltrim($url, '/');
    } else {
        return $base_url;
    }
}

/**
 * Executes a simple SELECT query using Prepared Statements (safe)
 * WARNING: This is a secure wrapper for prepared statements but is limited to simple SELECTs.
 * For complex queries, use direct prepared statements where the query logic resides.
 */
function query_secure($sql, $params = null, $types = "") {
    global $conn;
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("SQL Prepare Error: " . $conn->error);
        return [];
    }

    if ($params !== null) {
        // Use call_user_func_array for binding parameters
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while( $row = $result->fetch_assoc() ) {
        $rows[] = $row;
    }
    $stmt->close();
    return $rows;
}


function registrasi($data, $conn) {
    
    // --- Data Sanitization ---
    $name = trim($data["nama"]);
    $username = strtolower(trim($data["username"]));
    $password = $data["password"];
    $password2 = $data["password2"];

    // 1. Check if name/username are empty (Basic server-side validation)
    if (empty($name) || empty($username)) {
        // SECURITY FIX: Use generic alerts, do not expose SQL error details
        echo "<script>alert('Nama dan username tidak boleh kosong!');</script>";
        return false;
    }

    // 2. Check if username already exists (Prepared Statement)
    $sql_check = "SELECT username FROM curator WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    if (!$stmt_check) { 
        error_log("Check prepare failed: " . $conn->error); 
        return false;
    }
    
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('username sudah terdaftar!');</script>";
        $stmt_check->close();
        return false;
    }
    $stmt_check->close();

    // 3. Check password confirmation
    if( $password !== $password2 ) {
        echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
        return false;
    }

    // 4. CRITICAL SECURITY FIX: Use password_hash() (Ensures secure password storage)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 5. Insert new user (Prepared Statement)
    // SECURITY FIX: Specify columns; assumed format (name, username, password)
    $sql_insert = "INSERT INTO curator (name, username, password) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    
    if (!$stmt_insert) { 
        error_log("Insert prepare failed: " . $conn->error); 
        return false;
    }
    
    // sss: all fields treated as strings
    $stmt_insert->bind_param("sss", $name, $username, $hashed_password);
    $success = $stmt_insert->execute();
    $rows_affected = $stmt_insert->affected_rows;
    $stmt_insert->close();

    return $rows_affected;
}

?>