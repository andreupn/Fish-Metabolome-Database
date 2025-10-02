<?php
// Ensure session is started for CSRF and other checks
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "../_config/config.php";

// Ensure $conn is available
if (!isset($conn)) {
    die("Error: Database connection not available.");
}

// Function to securely output URLs or string data
function secure_url_output($url) {
    return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
}

// --- SECURITY FIX: Generate and store a CSRF token ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
// -----------------------------------------------------


if( isset($_SESSION['login']) ) {
    // ✅ Redirection Fix: Use server-side header redirection
    header("Location: " . base_url());
    exit;
}


if( isset($_POST["login"]) ) {

    // --- CSRF VALIDATION (Check before proceeding) ---
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // Log this error! Invalid CSRF is a sign of an attack attempt.
        die("Error: Invalid CSRF token. Login request blocked.");
    }
    // Note: Do NOT unset the CSRF token here since a failed login attempt should keep the token.
    // Unset it only upon successful login to prevent session fixation/reuse.
    // -------------------------------------------------

    // Inputs are NOT escaped, as Prepared Statements will handle it
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // 1. SQL Injection Fix: Use Prepared Statement for username check
    $stmt = $conn->prepare("SELECT username, password FROM curator WHERE username = ?");
    
    if (!$stmt) {
        die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if username was found
    if( $result->num_rows === 1 ) {

        // Check password
        $row = $result->fetch_assoc();
        
        // Ensure password_verify is used on the stored hash!
        if( password_verify($password, $row["password"]) ) {
            
            // Set session
            $_SESSION['login'] = true;
            $_SESSION['username'] = $username;
            
            // Unset the token upon successful login
            unset($_SESSION['csrf_token']);
            
            // ✅ Redirection Fix: Use server-side header redirection
            header("Location: " . base_url('dashboard')); // Redirect to dashboard after login
            exit; 
        }
    }
    
    $stmt->close();
    $error = true;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login - FMDB</title>
    <link href="<?=secure_url_output(base_url('_assets/css/bootstrap.min.css'));?>" rel="stylesheet">
    <link rel="icon" href="<?=secure_url_output(base_url('_assets/favicon.svg'))?>">
</head>
<body>
    <div id="wrapper">
        <div class="container" align="right">
            <div class="input-group" style="margin-top: 10px;">
                <a href="<?=secure_url_output(base_url('home/index.php'))?>" class=""><i class="glyphicon glyphicon-home"></i> Return to Homepage</a>
            </div>
            <div align="center" style="margin-top: 220px;">
              
                <?php if( isset($error) ) : ?>
                    <p style="color: red; font-style: italic;">Username / password salah</p>
                <?php endif; ?>
                
                <form action="" method="post" class="navbar-form">
                    
                    <input type="hidden" name="csrf_token" value="<?= secure_url_output($csrf_token) ?>">

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="input-group">
                        <input type="submit" name= "login" class="btn btn-primary" value="Login">
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script src="<?=secure_url_output(base_url('_assets/js/jquery.js'))?>"></script>
    <script src="<?=secure_url_output(base_url('_assets/js/bootstrap.min.js'))?>"></script>
</body>
</html>