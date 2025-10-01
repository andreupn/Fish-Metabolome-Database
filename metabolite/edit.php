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
        <h1>Metabolite</h1>
        <h4>
            <small>Edit Data Metabolite</small>
            <div class="pull-right">
                <a href="data.php" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-chevron-left"></i>Kembali</a>
            </div>
        </h4>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <?php
                $id = @$_GET['id'];
                $sql_metabolite = mysqli_query($conn, "SELECT * FROM metabolite WHERE metabolite_id = '$id'") or die (mysqli_error($conn));
                $data = mysqli_fetch_array($sql_metabolite);
                ?>
                <form action="proses.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama Metabolite</label>
                        <input type="hidden" name="id" value = "<?=$data['metabolite_id']?>">
                        <input type="hidden" name="structureLama" value = "<?=$data['structure']?>">
                        <input type="hidden" name="metaboliteLama" value = "<?=$data['metabolite_name']?>">
                        <input type="text" name="nama" id="nama" value = "<?=$data['metabolite_name']?>" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="detail">Detail</label>
                        <input type="textarea" name="detail" id="detail" value = "<?=$data['detail']?>" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="structure">Structure</label> <br>
                        <img src="../images/<?=$data['structure'];?>"> <br>
                        <input type="file" name="structure" id="structure" class="">
                    </div>
                    <div class="form-group pull-right">
                        <input type="submit" name="edit" value="Simpan" class="btn btn-success">
                    </div>
                </form>

            </div>

        </div>
  </div>

  <?php include_once('../_footer.php'); ?>
