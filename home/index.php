<?php
require_once "../_config/config.php";

/*
if(!isset($_SESSION['login'])) {
      echo "<script>window.location='".base_url('auth/login.php')."';</script>";
  }
*/
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
        <!--<a href="<?=base_url('home/download.php')?>" disabled >Download</a>-->
        <a role="link" aria-disabled="true">Download</a>

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

    <div class="stackimage">
      <div class="lakeimage"></div>
      <div class="lakeimagelayer">FISH METABOLITE DATABASE</div>
    </div>

    <div class="welcominginfo">
      <div class="fmdbisan">
        <h1>Welcome to FMDB version 1.0</h1>
        <br>
        <!-- <p>FMDB is an electronic database containing detailed information on metabolites produced by various species freshwater and marine fish.</p>-->
        <br>
        <p>The Fish Metabolome Database (FMDB) is a resourse containing open access metabolite datasets found in various fish species regarding their habitats. Fish metabolite data is important information that provides insights into the physiological and biochemical processes occurring in fish. The data can be used to several research purposes such as species identification, and biomarker analysis.
        Simple data searches make it easier to use data, with two browse feature options, namely species and metabolites. This database also provides spectrum data for each species that analyzed using 1 H-NMR (Nuclear Magnetic Resonance). Spectrum images can be downloaded for research purposes in accordance with ethics and citation provisions.</p>

      </div>
      <div class="socmedbox">Social Media Feed</div>
    </div>

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
