<?php

include_once('../_header.php');

?>

<!DOCTYPE html>
<html>
    <style>
        input[type=file] {
        width: 500px;
        max-width: 100%;
        color: #444;
        padding: 5px;
        background: #fff;
        border-radius: 3px;
        border: 1px solid grey ;
        }
    </style>
</html>

    <div class="box">
        <h1>Species</h1>
        <h4>
            <small>Edit Data Species</small>
            <div class="pull-right">
                <a href="data.php" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-chevron-left"></i>Kembali</a>
            </div>
        </h4>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <?php
                $id = @$_GET['id'];
                $sql_species = mysqli_query($conn, "SELECT * FROM species WHERE species_id = '$id'") or die (mysqli_error($conn));
                $data = mysqli_fetch_array($sql_species);
                ?>
                <form action="proses.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama Species</label>
                        <input type="hidden" name="id" value = "<?=$data['species_id']?>">
                        <input type="hidden" name="spectrumLama" value = "<?=$data['spectrum']?>">
                        <input type="hidden" name="speciesLama" value = "<?=$data['species_name']?>">
                        <input type="text" name="nama" id="nama" value = "<?=$data['species_name']?>" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="habitat">Habitat</label>
                        <input type="text" name="habitat" id="habitat" value = "<?=$data['habitat']?>" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id=location value = "<?=$data['location']?>" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="spectrum">Spectrum</label>
                        <img src="../images/<?=$data['spectrum'];?>"> <br>
                        <input type="file" name="spectrum" id="spectrum" class="">
                    </div>
                    <div class="form-group pull-right">
                        <input type="submit" name="edit" value="Simpan" class="btn btn-success">
                    </div>
                </form>

            </div>

        </div>
  </div>

  <?php include_once('../_footer.php'); ?>
