<?php
require_once "../_config/config.php";
?>

<!DOCTYPE html>
<html>
<head>
  <title>FMDB Homepage</title>
  <link rel="icon" type="image/x-icon" href="images/favicon.svg">
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
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
        <a href="<?=base_url('home/download.php')?>">Download</a>
        <div class="dropdownhome">
          <button class="dropbtn">About
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdownhome-content">
            <a href="<?=base_url('home/about.php')?>">About</a>
            <a href="<?=base_url('home/contact.php')?>">Contact Us</a>
          </div>
        </div>
        <a href="<?=base_url('auth/index.html')?>">Login</a>
      </div>
      <div class="kanan">
        <form id="searchForm">
            <input type="text" id="searchInput" name="searchInput" placeholder="Enter your search term" required>
            <button type="submit">Search</button>
        </form>
      </div>
    </div>

    <div class="container">
      <a href="https://www.brin.go.id/" target="_blank">
        <div class="logobrinhomepage">
          <img src="images/brin.png" alt="BRIN" width="200" height="75">
        </div>
      </a>
      <div class="shortdescription">
        <p>Quantitative data of fish metabolies for biomarkers and detection of artificial compounds.</p>
      </div>
    </div>
  </header>

    <main>
      <div class="searchpagetitle">
        <h2>Download</h2>
      </div>
      <br><br>


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
