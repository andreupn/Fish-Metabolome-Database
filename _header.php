<?php
require_once "_config/config.php";

if(!isset($_SESSION['login'])) {
      echo "<script>window.location='".base_url('auth/login.php')."';</script>";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Aplikasi FMDB</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url('_assets/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?=base_url('_assets/css/simple-sidebar.css');?>" rel="stylesheet">
    <link rel="icon" href="<?=base_url('_assets/favicon.svg')?>">
</head>
<body>
    <script src="<?=base_url('_assets/js/jquery.js')?>"></script>
    <script src="<?=base_url('_assets/js/bootstrap.min.js')?>"></script>
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
              <br>
                <li class="sidebar-brand">
                  <a href="https://www.brin.go.id/" target="_blank">
                    <div class="">
                      <img src="../home/images/brin.png" alt="BRIN" width="200" height="75">
                    </div>
                  </a>
                </li>
                <li class="sidebar-brand">
                    <a href="<?=base_url('dashboard')?>"> <span class="text-primary"><b>Fish Metabolite Database</b></span></a>
                </li>
                <li>
                    <a href="<?=base_url('home/index.php')?>">Front Page</a>
                </li>
                <li>
                    <a href="<?=base_url('dashboard')?>">Dashboard</a>
                </li>
                <li>
                    <a href="<?=base_url('quantity/browse_data.php')?>">Browse Fish metabolite Data</a>
                </li>
                <li>
                  <a href="<?=base_url('species/data.php')?>">Species Data</a>
                </li>
                <li>
                  <a href="<?=base_url('metabolite/data.php')?>">Metabolite Data</a>
                </li>
                <li>
                  <a href="<?=base_url('quantity/data.php')?>">Add Quantity</a>
                </li>
                <li>
                    <a href="<?=base_url('auth/logout.php')?>"><span class="text-danger">Logout</span></a>
                </li>
            </ul>
        </div>
        <div id="page-content-wrapper">
            <div class="container-fluid">
