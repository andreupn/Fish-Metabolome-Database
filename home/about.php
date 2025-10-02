<?php
// Ensure session is started if needed for base_url context
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "../_config/config.php";

// Function to securely output URLs or string data (XSS prevention)
function secure_url_output($url) {
    // Escapes special characters, preventing injection into HTML attributes like href or src
    return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
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
    <title>FMDB Homepage</title>
    <link href="<?=secure_url_output(base_url('_assets/css/bootstrap.min.css'));?>" rel="stylesheet">
    <link href="<?=secure_url_output(base_url('_assets/css/simple-sidebar.css'));?>" rel="stylesheet">
    <link rel="icon" href="<?=secure_url_output(base_url('_assets/favicon.svg'))?>">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
  <script src="<?=secure_url_output(base_url('_assets/js/jquery.js'))?>"></script>
  <script src="<?=secure_url_output(base_url('_assets/js/bootstrap.min.js'))?>"></script>
  <header>
    <div class="topcontainer">
      <div class="logo"></div>
      <div class="kiri">
        <a href="index.php">
          <h1 class="judul">FMDB</h1>
        </a>
      </div>
      <div class="navbar">
        <a href="<?=secure_url_output(base_url('home/metabolite.php'))?>">Browse</a>
        <a href="<?=secure_url_output(base_url('home/search.php'))?>">Search</a>
        <a role="link" aria-disabled="true">Download</a>

        <div class="dropdownhome">
          <button class="dropbtn">About
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdownhome-content">
            <a role="link" aria-disabled="true">About</a>
            <a href="<?=secure_url_output(base_url('home/contact.php'))?>">Contact Us</a>
          </div>
        </div>
        <a href="<?=secure_url_output(base_url('auth/index.html'))?>">Login</a>
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
      <div class="description">
        <h2>About Us</h2>
        <br>
        <h4>Welcome to FMDB Version 1.0</h4>
        <br>
        <p align="justify">The Fish Metabolome Database (FMDB) is a resourse containing open access metabolite datasets found in various fish species regarding their habitats. Fish metabolite data is important information that provides insights into the physiological and biochemical processes occurring in fish. The data can be used to several research purposes such as species identification, and biomarker analysis. Simple data searches make it easier to use data, with two browse feature options, namely species and metabolites. This database also provides spectrum data for each species that analyzed using 1 H-NMR (Nuclear Magnetic Resonance). Spectrum images can be downloaded for research purposes in accordance with ethics and citation provisions.</p>
        <br>
      </div>
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