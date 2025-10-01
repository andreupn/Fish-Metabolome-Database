<?php include_once('../_header.php'); ?>

<div class="box">
    <h1>FMDB Data</h1>
    <h4>
        <small>Data Kompilasi</small>
        <div class="pull-right">
            <a href="" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-refresh"></i></a>
        </div>
    </h4>
    <div style="margin-bottom: 20px; ">
        <form class="form-inline" action="" method="post">
            <div class="form-group">
                <input type="text" name="pencarian" class="form-control" placeholder="Masukan kata kunci!">
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

<?php include_once('../_footer.php'); ?>
