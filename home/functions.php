<?php
// Ensure session is started if needed elsewhere, although usually done in a header file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function for XSS prevention when displaying alerts
function secure_alert_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// koneksi ke databse
// 1. SECURITY FIX: Use Object-Oriented mysqli for better error handling/prepared statements
$conn = new mysqli("localhost", "root", "", "fmdb");

if( $conn->connect_errno ) {
    // 2. SECURITY FIX: Log error instead of exposing details
    error_log("Failed to connect to MySQL: " . $conn->connect_error);
    die("Database connection error."); 
}

/**
 * Executes a simple, general SELECT query using the vulnerable procedural style.
 * WARNING: This function is fundamentally insecure if used with external input.
 * In a secure application, you should always use Prepared Statements directly.
 * For true security, this function should be removed or strictly limited to internal,
 * hardcoded queries. It is left here primarily to show it should be replaced.
 */
function query($query) {
    global $conn;
    // ⚠️ CRITICAL VULNERABILITY: Do not use this function with user-supplied data!
    $result = mysqli_query($conn, $query); 
    if (!$result) {
        error_log("Insecure Query Error: " . mysqli_error($conn) . " Query: " . $query);
        return [];
    }
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }
    return $rows;
}


function registrasi($data) {
    global $conn;

    // Inputs are sanitized/validated BEFORE SQL interaction
    $Nama = trim($data["nama"]);
    $username = strtolower(trim($data["username"]));
    $password = $data["password"];
    $password2 = $data["password2"];

    // 1. Check for existing username (Prepared Statement)
    // Avoids SQL Injection, which was present in the original code
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

    // 2. Check Password Confirmation
    if( $password !== $password2 ) {
        echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
        return false;
    }

    // 3. CRITICAL SECURITY FIX: Password Hashing (Ensures secure password storage)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 4. Insert new user (Prepared Statement)
    // Fixes SQL Injection in the INSERT query
    // SECURITY FIX: Explicitly list columns and use placeholders
    $sql_insert = "INSERT INTO curator (name, username, password) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    
    if (!$stmt_insert) { 
        error_log("Insert prepare failed: " . $conn->error); 
        return false;
    }
    
    $stmt_insert->bind_param("sss", $Nama, $username, $hashed_password);
    $success = $stmt_insert->execute();
    $rows_affected = $stmt_insert->affected_rows;
    $stmt_insert->close();

    return $rows_affected;
}
?>