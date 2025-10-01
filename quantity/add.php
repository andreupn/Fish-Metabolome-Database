<?php

include_once('../_header.php');

?>

    <div class="box">
        <h1>FMDB Quantity</h1>
        <h4>
            <small>Tambah Data Quantity</small>
            <div class="pull-right">
                <a href="data.php" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-chevron-left"></i>Kembali</a>
            </div>
        </h4>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <form action="proses.php" method="post">
                    <div class="form-group">
                        <label for="species">Species</label>
                        <select name="species" id= "species" class="form-control" required>
                            <option value="">- Pilih Species-</option>
                            <?php
                            $sql_species = mysqli_query($conn, "SELECT * FROM species") or die(mysqli_error($conn));
                            while($data_species = mysqli_fetch_array($sql_species)) {
                                echo '<option value="'.$data_species['species_id'].'">'.$data_species['species_name'].'</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="metabolite">Metabolite</label>
                        <select name="metabolite" id= "metabolite" class="form-control" required>
                            <option value="">- Pilih Metabolite -</option>
                            <?php
                            $sql_metabolite = mysqli_query($conn, "SELECT * FROM metabolite") or die(mysqli_error($conn));
                            while($data_metabolite = mysqli_fetch_array($sql_metabolite)) {
                                echo '<option value="'.$data_metabolite['metabolite_id'].'">'.$data_metabolite['metabolite_name'].'</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="text" name="quantity" id="quantity" class="form-control" required autofocus>
                    </div>
                    <div class="form-group pull-right">
                        <input type="submit" name="add" value="Simpan" class="btn btn-success">
                        <input type="reset" name="reset" value="reset" class="btn btn-default">
                    </div>
                </form>

            </div>

        </div>
  </div>

  <?php include_once('../_footer.php'); ?>
