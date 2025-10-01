<?php

require_once "../_config/config.php";

if( isset($_SESSION['login']) ) {
    echo "<script>window.location='".base_url()."';</script>";
    exit;
}


if( isset($_POST["login"]) ) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM curator WHERE
        username = '$username'");

    // cek username
    if( mysqli_num_rows($result) === 1 ) {

          // cek password
          $row = mysqli_fetch_assoc($result);
          if( password_verify($password, $row["password"]) ) {
              // set session
              $_SESSION['login'] = true;
              $_SESSION['username'] = $username;
              echo "<script>window.location='".base_url()."';</script>";
          }

    }
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
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url('_assets/css/bootstrap.min.css');?>" rel="stylesheet">
    <link rel="icon" href="<?=base_url('_assets/favicon.svg')?>">
</head>
<body>
    <div id="wrapper">
        <div class="container" align="right">
            <div class="input-group" style="margin-top: 10px;">
              <a href="<?=base_url('home/index.php')?>" class=""><i class="glyphicon glyphicon-home"></i> Return to Homepage</a>
            </div>
            <div align="center" style="margin-top: 220px;">
              <?php if( isset($error) ) : ?>
                  <p style="color: red; font-style: italic;">Username / password salah</p>
              <?php endif; ?>
                <form action="" method="post" class="navbar-form">
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
    <script src="<?=base_url('_assets/js/jquery.js')?>"></script>
    <script src="<?=base_url('_assets/js/bootstrap.min.js')?>"></script>
</body>
</html>



<!--
