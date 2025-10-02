<?php
// Ensure session is started if not done in config.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "_config/config.php";

// Function to securely output URLs or string data
function secure_url_output($url) {
    // Only URL encode the query segment if any; otherwise, just HTML encode
    return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
}


if(!isset($_SESSION['login'])) {
    // ✅ SECURITY FIX: Use server-side header redirection (Preferred)
    header("Location: " . base_url('auth/login.php'));
    exit; // Stop execution after sending the header
    
    // Original (Vulnerable to XSS if base_url() returned un-encoded user input):
    // echo "<script>window.location='".base_url('auth/login.php')."';</script>";
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
    <title>Aplikasi FMDB</title>
    <link href="<?=secure_url_output(base_url('_assets/css/bootstrap.min.css'));?>" rel="stylesheet">
    <link href="<?=secure_url_output(base_url('_assets/css/simple-sidebar.css'));?>" rel="stylesheet">
    <link rel="icon" href="<?=secure_url_output(base_url('_assets/favicon.svg'))?>">
</head>
<body>
    <script src="<?=secure_url_output(base_url('_assets/js/jquery.js'))?>"></script>
    <script src="<?=secure_url_output(base_url('_assets/js/bootstrap.min.js'))?>"></script>
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
              <br>
                <li class="sidebar-brand">
                  <a href="https://www.brin.go.id/" target="_blank">
                    <div class="">
                      <img src="../home/images/brin.png" alt="BRIN" width="200" height="75">
                    </div>
                  </a>
                </li>
                <li class="sidebar-brand">
                    <a href="<?=secure_url_output(base_url('dashboard'))?>"> <span class="text-primary"><b>Fish Metabolite Database</b></span></a>
                </li>
                <li>
                    <a href="<?=secure_url_output(base_url('home/index.php'))?>">Front Page</a>
                </li>
                <li>
                    <a href="<?=secure_url_output(base_url('dashboard'))?>">Dashboard</a>
                </li>
                <li>
                    <a href="<?=secure_url_output(base_url('quantity/browse_data.php'))?>">Browse Fish metabolite Data</a>
                </li>
                <li>
                  <a href="<?=secure_url_output(base_url('species/data.php'))?>">Species Data</a>
                </li>
                <li>
                  <a href="<?=secure_url_output(base_url('metabolite/data.php'))?>">Metabolite Data</a>
                </li>
                <li>
                  <a href="<?=secure_url_output(base_url('quantity/data.php'))?>">Add Quantity</a>
                </li>
                <li>
                    <a href="<?=secure_url_output(base_url('auth/logout.php'))?>"><span class="text-danger">Logout</span></a>
                </li>
            </ul>
        </div>
        <div id="page-content-wrapper">
            <div class="container-fluid">