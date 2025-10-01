<?php
require_once "../_config/config.php";
//require 'functions.php';

if( isset($_POST["register"]) ) {

    if( registrasi($_POST) > 0) {
        echo "<script>
                alert('User baru berhasil ditambahkan!');
                window.location='index.html';
              </script>";
    } else {
        echo mysqli_error($conn);
      }
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
  <div align= "center" class="container" style="margin-top: 100px;">
      <h2>Halaman Registerasi Kurator</h2>
      <br>
      <div class="row">
          <div class="col-lg-6 col-lg-offset-3">
              <form action="" method="post" >
                  <div class="form-group">
                      <label for="nama">Input Nama</label>
                      <input type="text" name="nama" id="nama" class="form-control" required autofocus>
                  </div>
                  <div class="form-group">
                    <label for="nama">Input Username</label>
                    <input type="text" name="username" id="username" class="form-control" required autofocus>
                  </div>
                  <div class="form-group">
                      <label for="password">Input Password</label>
                      <input type="password" name="password" id="password" class="form-control" required autofocus>
                  </div>
                  <div class="form-group">
                      <label for="password2">Konfirmasi Password</label>
                      <input type="password" name="password2" id="password2" class="form-control" required autofocus>
                  </div>
                  <div class="form-group pull-right">
                      <input type="submit" name="register" value="Register!" class="btn btn-success">
                      <input type="reset" name="reset" value="reset" class="btn btn-default">
                  </div>
              </form>

          </div>

      </div>
    <script src="<?=base_url('_assets/js/jquery.js')?>"></script>
    <script src="<?=base_url('_assets/js/bootstrap.min.js')?>"></script>
</body>
</html>
