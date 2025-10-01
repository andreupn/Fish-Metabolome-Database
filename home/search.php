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
        <a role="link" aria-disabled="true">Search</a>
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
      <div class="box">
        <h1><strong>Search Fish Metabolite Data</strong></h1>
        <br>

          <?php
          // Initialize variables
          $searchTerm = "";
          $showTable = false;

          // Check if the form is submitted
          if (isset($_POST['kata_kunci'])) {
              // Set $showTable to true to indicate that the table should be displayed
              $showTable = true;
          }


          $Species="";
          $Metabolite="";
          if (isset($_POST['kolom'])) {

              if ($_POST['kolom']=="Species") {
                  $Species="selected";
              } else {
                  $Metabolite="selected";
              }
          }

          $kata_kunci="";
          if (isset($_POST['kata_kunci'])) {
              $kata_kunci=$_POST['kata_kunci'];
          }
          ?>

          <div style="margin-bottom: 20px; ">
            <form class= "form-inline" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                  <div class="form-group">
                      <select class="form-control" name="kolom" required display: inline-block>
                              <option value="" >Select Search Term</option>
                              <option value="Species" <?php echo $Species; ?> >Species Name</option>
                              <option value="Metabolite" <?php echo $Metabolite; ?> >Metabolite Name</option>
                       </select>
                  </div>
                  <div class="form-group">
                      <input type="text" name="kata_kunci" value="<?php echo $kata_kunci;?>" class="form-control" display: inline-block required/>
                  </div>
                  <div class="form-group">
                      <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hiden="true"></span></button>
                  </div>
                  <div class="form-group">
                      <input type="reset" name="reset" value="reset" class="btn btn-default">
                  </div>

          </form>
          </div>

        <br>

        <?php

        include_once('../_config/config.php');

        if ($showTable) {

            if (isset($_POST['kata_kunci'])) {
                $kata_kunci=trim(mysqli_real_escape_string($conn, $_POST['kata_kunci']));

                $kolom="";
                if ($_POST['kolom']=="Species") {
                    $kolom="Species";
                    $sql="select * from species_metabolite where $kolom like '%".$kata_kunci."%' ";
                    $query = "SELECT * FROM species_metabolite";

                } else {
                    $kolom="Metabolite";
                    $sql="select * from metabolite_species where $kolom like '%".$kata_kunci."%' ";
                    $query = "SELECT * FROM metabolite_species";

                }

            } else {
                $sql="select * from species_metabolite";
            }

        $hasil=mysqli_query($conn,$query);
        while ($row = mysqli_fetch_array($hasil, MYSQLI_ASSOC)) {
            $tables[]=$row;
        }
        echo '<table class="table table-striped table-bordered table-hover"><tr>';

        foreach ($tables[0] as $key => $value)
        {
            echo "<th>{$key}</th>";
            $keys[] = $key;
        }
        echo '</tr>';

        $hasil=mysqli_query($conn,$sql);
        $no=0;
        if ($_POST['kolom']=="Species") {
        while ($data = mysqli_fetch_array($hasil)) {
            $no++;
            ?>

            <tr>
              <!--<td><?=$no++?></td>-->
              <td><?=$data['Species']?></td>
              <td><?=$data['Habitat']?></td>
              <td><?=$data['Location']?></td>
              <td><img src="../images/<?=$data['Spectrum']?>"></td>
              <td><?=$data['Metabolite']?></td>
              <td><?=$data['Quantity']?></td>
            </tr>
            <?php
        }
        } else {
        while ($data = mysqli_fetch_array($hasil)) {
            $no++;
            ?>
            <tr>
              <!--<td><?=$no++?></td>-->
              <td><?=$data['Metabolite']?></td>
              <td><?=$data['Detail']?></td>
              <td><img src="../images/<?=$data['Structure']?>"></td>
              <td><?=$data['Quantity']?></td>
              <td><?=$data['Species']?></td>
            </tr>

            <?php


          }
        }
        echo '</table>';

        }
          ?>




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
