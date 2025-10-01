<?php
require_once "../_config/config.php";

mysqli_query($conn, "DELETE from fmdb WHERE fmdb_id = '$_GET[id]'") or die (mysqli_error($conn));
echo "<script>window.location='data.php';</script>";

 ?>
