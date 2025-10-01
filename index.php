<?php
require_once "_config/config.php";

if(isset($_SESSION['login'])) {
      echo "<script>window.location='".base_url('dashboard')."';</script>";
  } else {
      echo "<script>window.location='".base_url('home/index.php')."';</script>";
  }
?>
