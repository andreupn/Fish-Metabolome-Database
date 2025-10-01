<?php

include_once('../_header.php');

?>

    <div class="box">
        <h1>Species</h1>
        <h4>
            <small>Tambah Data Species</small>
            <div class="pull-right">
                <a href="data.php" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-chevron-left"></i>Kembali</a>
            </div>
        </h4>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <form action="proses.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama Species</label>
                        <input type="text" name="nama" id="nama" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="habitat">Habitat</label>
                        <input type="text" name="habitat" id="habitat" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id=location class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="spectrum">Spectrum</label>
                        <input type="file" name="spectrum" id="spectrum" class="form-control" required autofocus>
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
