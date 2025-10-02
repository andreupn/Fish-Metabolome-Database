<?php
// Ensure session is started for security purposes (though not directly used here)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "classes/class.phpmailer.php";
$mail = new PHPMailer();

// --- CRITICAL SECURITY FIX: Sanitize all user input ---

// Use filter_input for cleaner, secure input retrieval
$sender_email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$sender_name  = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING); // Use STRING for basic HTML stripping
$subject      = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
$message_body = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Highest level of sanitization for message body

// Basic validation check
if (!$sender_email || !$sender_name || !$subject || !$message_body) {
    die("Error: Invalid or missing form data.");
}

// --------------------------------------------------------

$mail->IsSMTP();
$mail->SMTPSecure = 'ssl';

// ⚠️ SECURITY NOTE: Hardcoding credentials directly in PHP files is highly discouraged.
// Use environment variables or a configuration system instead.
$mail->Host = "mail.fmdb.info"; 
$mail->SMTPDebug = 0;
$mail->Port = 465;
$mail->SMTPAuth = true;
$mail->Timeout = 60;
$mail->SMTPKeepAlive = true;
$mail->Username = ""; 
$mail->Password = ""; 

// 1. SECURITY FIX: Pass sanitized user input
// Name and email are automatically quoted by PHPMailer, but sanitization is essential.
$mail->SetFrom($sender_email, $sender_name); 

// 2. SECURITY FIX: Pass sanitized subject
$mail->Subject = $subject;

// 3. SECURITY FIX: Add recipient and message body
$mail->AddAddress("admin@fmdb.info", "Website Admininistrator");

// Use the sanitized message body. PHPMailer will send this as plain text 
// (though MsgHTML usually implies HTML, Full_SPECIAL_CHARS converts potential XSS).
// If you want to allow limited HTML, you'd use a stricter library like HTML Purifier.
$mail->MsgHTML($message_body); 

if($mail->Send()) {
    echo "Message has been sent";
    // For reliable redirection, use a header redirect (if no output has been sent yet)
    // header("Location: Contact.php"); 
} else {
    // Log the error for debugging, but show a generic failure message to the user.
    error_log("PHPMailer Error: " . $mail->ErrorInfo); 
    echo "Failed to sending message";
}
?>