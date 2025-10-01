<?php
require_once "../_config/config.php";

mysqli_query($conn, "DELETE from metabolite WHERE metabolite_id = '$_GET[id]'") or die (mysqli_error($conn));
echo "<script>window.location='data.php';</script>";

 ?>
