<?php include_once('../_header.php'); ?>

<div class="box">
    <h1>Species</h1>
    <h4>
        <small>Data Species</small>
        <div class="pull-right">
            <a href="" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-refresh"></i></a>
            <a href="add.php" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-plus"></i>Tambah Species</a>
        </div>
    </h4>
    <div style="margin-bottom: 20px; ">
        <form class="form-inline" action="" method="post">
            <div class="form-group">
                <input type="text" name="pencarian" class="form-control" placeholder="Pencarian Species">
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
                    <th><i class="glyphicon glyphicon-cog"></i></th>
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
                    $sql = "SELECT * FROM species WHERE species_name LIKE '%$pencarian%' OR
                                                        habitat LIKE '%$pencarian%' OR
                                                        location LIKE '%$pencarian%'";
                    $query = $sql;
                    $queryJml = $sql;
                } else {
                    $query = "SELECT * FROM species LIMIT $posisi, $batas";
                    $queryJml = "SELECT* FROM species";
                    $no = $posisi + 1;
                }
            } else {
              $query = "SELECT * FROM species LIMIT $posisi, $batas";
              $queryJml = "SELECT* FROM species";
              $no = $posisi + 1;
            }

            $sql_species = mysqli_query($conn, $query) or die (mysqli_error($conn));
            if(mysqli_num_rows($sql_species) > 0) {
                while($data = mysqli_fetch_array($sql_species)) { ?>
                    <tr>
                        <td><?=$no++?></td>
                        <td><?=$data['species_name']?></td>
                        <td><?=$data['habitat']?></td>
                        <td><?=$data['location']?></td>
                        <td><img src="../images/<?=$data['spectrum']?>"></td>
                        <td class="text-center">
                            <a href="edit.php?id=<?=$data['species_id']?>" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                            <a href="delete.php?id=<?=$data['species_id']?>" onclick="return confirm('Yakin data akan dihapus?')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></a>
                        </td>
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
