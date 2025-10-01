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
        <h1>Metabolite Quantity</h1>
        <h4>
            <small>Edit Data Quantity Metabolite</small>
            <div class="pull-right">
                <a href="data.php" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-chevron-left"></i>Kembali</a>
            </div>
        </h4>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <?php
                $id = @$_GET['id'];
                $sql_fmdb = mysqli_query($conn, "SELECT * FROM fmdb WHERE fmdb_id = '$id'") or die (mysqli_error($conn));
                $data = mysqli_fetch_array($sql_fmdb);
                $tmp_species_id = $data['species_id'];
                $tmp_metabolite_id = $data['metabolite_id'];
                $sql_tmp_species = mysqli_query($conn, "SELECT * FROM species WHERE species_id = '$tmp_species_id'") or die (mysqli_error($conn));
                $data_tmp_species = mysqli_fetch_array($sql_tmp_species);
                $sql_tmp_metabolite = mysqli_query($conn, "SELECT * FROM metabolite WHERE metabolite_id = '$tmp_metabolite_id'") or die (mysqli_error($conn));
                $data_tmp_metabolite = mysqli_fetch_array($sql_tmp_metabolite);
                ?>
                <form action="proses.php" method="post">
                    <div class="form-group">
                        <label for="edit_species">Data Species Awal</label>
                        <input type="text" name="edit_species" id="edit_species" value = "<?=$data_tmp_species['species_name']?>" class="form-control" disabled="disabled">
                        <label for="species">Pilih Species Baru</label>
                        <input type="hidden" name="id" value = "<?=$data['fmdb_id']?>">
                        <input type="hidden" name="speciesIdLama" value = "<?=$data['species_id']?>">
                        <input type="hidden" name="metaboliteIdLama" value = "<?=$data['metabolite_id']?>">
                        <select name="species" id= "species" class="form-control" required>
                            <!--<option value=""><?=$data_tmp_species['species_id']. "-" .$data_tmp_species['species_name']?></option>-->
                            <option value="">Pilih Species</option>
                            <?php
                            $sql_species = mysqli_query($conn, "SELECT * FROM species") or die(mysqli_error($conn));
                            while($data_species = mysqli_fetch_array($sql_species)) {
                                echo '<option value="'.$data_species['species_id'].'">'.$data_species['species_name'].'</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_metabolite">Data Metabolite Awal</label>
                        <input type="text" name="edit_metabolite" id="edit_metabolite" value = "<?=$data_tmp_metabolite['metabolite_name']?>" class="form-control" disabled="disabled">
                        <label for="metabolite">Pilih Metabolite Baru</label>
                        <select name="metabolite" id= "metabolite" class="form-control" required>
                            <!--<option value=""><?=$data_tmp_metabolite['metabolite_id']. "-" .$data_tmp_metabolite['metabolite_name']?></option>-->
                            <option value="">Pilih Metabolite Baru</option>
                            <?php
                            $sql_metabolite = mysqli_query($conn, "SELECT * FROM metabolite") or die(mysqli_error($conn));
                            while($data_metabolite = mysqli_fetch_array($sql_metabolite)) {
                                echo '<option value="'.$data_metabolite['metabolite_id'].'">'.$data_metabolite['metabolite_name'].'</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label> <br>
                        <input type="text" name="quantity" id="quantity" value = "<?=$data['quantity']?>" class="form-control" required autofocus>
                    </div>
                    <div class="form-group pull-right">
                        <input type="submit" name="edit" value="Simpan" class="btn btn-success">
                    </div>
                </form>

            </div>

        </div>
  </div>

  <?php include_once('../_footer.php'); ?>
