<?php
require_once "../_config/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>FMDB Homepage</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url('_assets/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?=base_url('_assets/css/simple-sidebar.css');?>" rel="stylesheet">
    <link rel="icon" href="<?=base_url('_assets/favicon.svg')?>">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
  <script src="<?=base_url('_assets/js/jquery.js')?>"></script>
  <script src="<?=base_url('_assets/js/bootstrap.min.js')?>"></script>
  <header>
    <div class="topcontainer">
      <div class="logo"></div>
      <div class="kiri">
        <a href="index.php">
          <h1 class="judul">FMDB</h1>
        </a>
      </div>
      <div class="navbar">
        <!--
        <div class="dropdownhome">
          <button class="dropbtn">Browse
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdownhome-content">
            <a href="<?=base_url('home/metabolite.php')?>">Metabolite</a>
            <a href="<?=base_url('home/species.php')?>">Species</a>
          </div>
        </div>
        -->
        <a href="<?=base_url('home/metabolite.php')?>">Browse</a>
        <a href="<?=base_url('home/search.php')?>">Search</a>
        <!--<a href="<?=base_url('home/download.php')?>" disabled >Download</a>-->
        <a role="link" aria-disabled="true">Download</a>

        <div class="dropdownhome">
          <button class="dropbtn">About
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdownhome-content">
            <a href="<?=base_url('home/about.php')?>">About</a>
            <a role="link" aria-disabled="true">Contact Us</a>
          </div>
        </div>
        <a href="<?=base_url('auth/index.html')?>">Login</a>
      </div>
      <div class="kanan">
        <form class="form-inline" action="metabolite.php" method="post">
            <div class="form-group">
                <input type="text" name="pencarian" class="form-control" placeholder="Enter your search term!">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hiden="true"></span></button>
            </div>
        </form>
      </div>
    </div>

    <div class="containerhomepage">
      <a href="https://www.brin.go.id/" target="_blank">
        <div class="logobrinbrowsepage">
          <img src="images/brin.png" alt="BRIN" width="200" height="75">
        </div>
      </a>
      <div class="shortdescriptionbrowsepage">
        <p>Quantitative data of fish metabolies for biomarkers and detection of artificial compounds.</p>
      </div>
    </div>
  </header>

    <main>
        <h2>Contact Information</h2>
        <br>
        <p>You can reach us using the following contact details:</p>

        <address>
            <strong>Badan Riset dan Inovasi Nasional</strong><br>
            Gedung BJ. Habibie, Jl. M. H. Thamrin No. 8 <br>
            Jakarta Pusat 10340.  <br>
            Whatsapp: +62811-1933-3639<br>
            Email: ppid@brin.go.id
        </address>

        <br>
        <h2>Contact Form</h2>
        <br>
        <form class="form-inline" action="action_form.php" method="post">
            <div class="">
                <input type="text" id="name" name="name" class="form-control" placeholder="Full Name" required autofocus> <br><br>
            </div>
            <div class="">
                <input type="text" id="email" name="mail" class="form-control" placeholder="Your Email" required autofocus> <br><br>
            </div>
            <div class="">
                <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject" required autofocus> <br><br>
            </div>
            <div class="">
                <textarea id="message" name="message" class="form-control" rows= "3" placeholder="Message" required autofocus></textarea><br><br><br>
            </div>
            <div class="" >
              <input type="submit" name="submit" value="Send Mail" class="btn btn-primary">
              <a href="" <i class="glyphicon glyphicon-refresh"></i></a>
            </div>
        </form>
    </main>

    <footer>
      <div class="footcontainer">
        <div class="projectinfo">
          <h2 align="justify">This project is a collaboration between the Food Proces Technology Research Center and Data and Information Science Research Center.</h2>
          <h2 align="justify">National Research and Innovation Agency</h2>
        </div>
        <div class="address">
          <h2>BRIN</h2>
          <h2>Badan Riset dan Inovasi Nasional</h2>
          <h2 align="justify">Alamat: Gedung BJ. Habibie, Jl. M. H. Thamrin No. 8 Jakarta Pusat 10340. Whatsapp: +62811-1933-3639 ; Email: ppid@brin.go.id</h2>
        </div>
      </div>
    </footer>

  </body>
  </html>
</body>
</html>
