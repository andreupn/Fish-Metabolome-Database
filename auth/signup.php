<?php
// functions.php

function registrasi($data, $conn) {
    
    // --- Data Sanitization (Trimming is enough before binding) ---
    $nama = trim($data['nama']);
    $username = strtolower(stripslashes(trim($data['username']))); // Ensure consistent casing
    $password = $data['password'];
    $password2 = $data['password2'];

    // --- Validation Checks ---
    if (empty($nama) || empty($username) || empty($password) || empty($password2)) {
        // Handle empty fields error (e.g., return 0 or throw exception)
        return 0; 
    }

    if ($password !== $password2) {
        // Handle password mismatch error
        return 0; 
    }
    
    // --- SQL Injection Fix: Check for existing username (Prepared Statement) ---
    $stmt_check = $conn->prepare("SELECT username FROM users WHERE username = ?");
    if (!$stmt_check) { return 0; }
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    
    if ($result->num_rows > 0) {
        // Handle username already exists error
        $stmt_check->close();
        return 0; 
    }
    $stmt_check->close();

    // --- CRITICAL SECURITY: Password Hashing ---
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // --- SQL Injection Fix: Insert new user (Prepared Statement) ---
    $sql_insert = "INSERT INTO users (name, username, password) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    
    if (!$stmt_insert) { return 0; }
    
    // sss: 3 strings (name, username, hashed_password)
    $stmt_insert->bind_param("sss", $nama, $username, $hashed_password);
    $success = $stmt_insert->execute();
    $stmt_insert->close();
    
    return $success ? 1 : 0;
}
?>