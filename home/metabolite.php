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
        <a role="link" aria-disabled="true">Browse</a>
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
          <h1><strong>Browse Fish Metabolite Data</strong></h1>
          <br>
          <h4>
              <div class="pull-right">
                  <a href="" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-refresh"></i></a>
              </div>
          </h4>
          <div style="margin-bottom: 20px; ">
              <form class="form-inline" action="" method="post">
                  <div class="form-group">
                      <input type="text" name="pencarian" class="form-control" placeholder="Enter your search term!">
                  </div>
                  <div class="form-group">
                      <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hiden="true"></span></button>
                  </div>
              </form>
          </div>
          <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">
                  <thead>
                      <tr>
                          <th>No.</th>
                          <th>Species</th>
                          <th>Habitat</th>
                          <th>Location</th>
                          <th>Spectrum</th>
                          <th>Metabolite</th>
                          <th>Detail</th>
                          <th>Structure</th>
                          <th>Quantity</th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php
                  $batas = 5;
                  $hal = @$_GET['hal'];
                  if(empty($hal)) {
                      $posisi = 0;
                      $hal = 1;
                  } else {
                      $posisi = ($hal - 1) * $batas;
                  }
                  $no = 1;
                  if($_SERVER['REQUEST_METHOD'] == "POST" ) {
                      $pencarian = trim(mysqli_real_escape_string($conn, $_POST['pencarian']));
                      if($pencarian != '') {
                          $sql = "SELECT * FROM fmdb
                                    INNER JOIN species ON fmdb.species_id = species.species_id
                                    INNER JOIN metabolite ON fmdb.metabolite_id = metabolite.metabolite_id
                                    WHERE species_name LIKE '%$pencarian%' OR
                                          habitat LIKE '%$pencarian%' OR
                                          location LIKE '%$pencarian%' OR
                                          metabolite_name LIKE '%$pencarian%' OR
                                          detail LIKE '%$pencarian%' OR
                                          quantity LIKE '%$pencarian%' ";
                          $query = $sql;
                          $queryJml = $sql;
                      } else {
                          $query = "SELECT * FROM fmdb
                                    INNER JOIN species ON fmdb.species_id = species.species_id
                                    INNER JOIN metabolite ON fmdb.metabolite_id = metabolite.metabolite_id LIMIT $posisi, $batas";
                          $queryJml = "SELECT * FROM fmdb
                                    INNER JOIN species ON fmdb.species_id = species.species_id
                                    INNER JOIN metabolite ON fmdb.metabolite_id = metabolite.metabolite_id";
                          $no = $posisi + 1;
                      }
                  } else {
                    $query = "SELECT * FROM fmdb
                              INNER JOIN species ON fmdb.species_id = species.species_id
                              INNER JOIN metabolite ON fmdb.metabolite_id = metabolite.metabolite_id LIMIT $posisi, $batas";
                    $queryJml = "SELECT * FROM fmdb
                              INNER JOIN species ON fmdb.species_id = species.species_id
                              INNER JOIN metabolite ON fmdb.metabolite_id = metabolite.metabolite_id";
                    $no = $posisi + 1;
                  }

                  $sql_quantity = mysqli_query($conn, $query) or die (mysqli_error($conn));
                  if(mysqli_num_rows($sql_quantity) > 0) {
                      while ($data = mysqli_fetch_array($sql_quantity)) { ?>
                        <tr>
                            <td><?=$no++?>.</td>
                            <td><?=$data['species_name']?></td>
                            <td><?=$data['habitat']?></td>
                            <td><?=$data['location']?></td>
                            <td><img src="../images/<?=$data['spectrum']?>"></td>
                            <td><?=$data['metabolite_name']?></td>
                            <td><?=$data['detail']?></td>
                            <td><img src="../images/<?=$data['structure']?>"></td>
                            <td><?=$data['quantity']?></td>
                        </tr>
                      <?php
                      }
                    } else {
                        echo "<tr><td colspan=\"4\" align=\"center\">Data tidak ditemukan</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
            $pencarian = isset($_POST['pencarian']) ? $_POST['pencarian'] : '';
            if($pencarian == '') { ?>
                <div style="float: left;">
                    <?php
                    $jml = mysqli_num_rows(mysqli_query($conn, $queryJml));
                    echo "Jumlah Data : <b>$jml</b>";
                    ?>
                </div>
                <div style="float:right;">
                    <ul class="pagination pagination-sm" style="margin:0">
                      <?php
                        $jml_hal = ceil($jml / $batas);
                        for ($i=1; $i <= $jml_hal; $i++) {
                            if($i != $hal) {
                                echo "<li><a href=\"?hal=$i\">$i</a></li>";
                            } else {
                                echo "<li class=\"active\"><a>$i</a></li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
                <?php
              } else {
                    echo "<div style=\"float:left;\">";
                    $jml = mysqli_num_rows(mysqli_query($conn, $queryJml));
                    echo "Data Hasil Pencarian : <b>$jml</b>";
                    echo "</div>";
                }
            ?>
        </div>
    </main>
    <br>

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
