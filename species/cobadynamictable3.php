<!DOCTYPE html>
<html>
<head>
    <!-- Load file CSS Bootstrap offline -->
    <link rel="stylesheet" href="../_assets/css/bootstrap.min.css">

</head>
<body>
<div class="container">
    <br>
    <h4>Menampilkan Data pada Species berdasarkan pilihan Combo Box di PHP</h4>

    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
    <div class="form-group">
        <label for="sel1">Kata Kunci:</label>
        <?php

        // Initialize variables
        $searchTerm = "";
        $showTable = false;

        // Check if the form is submitted
        if (isset($_POST['kata_kunci'])) {
            // Set $showTable to true to indicate that the table should be displayed
            $showTable = true;
        }

        $Species="";
        $Metabolite="";
        if (isset($_POST['kolom'])) {

            if ($_POST['kolom']=="Species") {
                $Species="selected";
            } else {
                $Metabolite="selected";
            }
        }

        $kata_kunci="";
        if (isset($_POST['kata_kunci'])) {
            $kata_kunci=$_POST['kata_kunci'];
        }
        ?>
        <select class="form-control" name="kolom" required display: inline-block>
                <option value="" >Silahkan pilih kolom dulu</option>
                <option value="Species" <?php echo $Species; ?> >Nama Species</option>
                <option value="Metabolite" <?php echo $Metabolite; ?> >Nama Metabolite</option>
         </select>
        <input type="text" name="kata_kunci" value="<?php echo $kata_kunci;?>" class="form-control" display: inline-block required/>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-info" value="Pilih">
    </div>
    </form>

        <br>
        <?php
        include_once('../_config/config.php');
        if ($showTable) {

        if (isset($_POST['kata_kunci'])) {
            $kata_kunci=trim($_POST['kata_kunci']);

            $kolom="";
            if ($_POST['kolom']=="Species") {
                $kolom="Species";
                $sql="select * from species_metabolite where $kolom like '%".$kata_kunci."%' ";
                $query = "SELECT * FROM species_metabolite";

            } else {
                $kolom="Metabolite";
                $sql="select * from metabolite_species where $kolom like '%".$kata_kunci."%' ";
                $query = "SELECT * FROM metabolite_species";

            }

        } else {
            $sql="select * from species_metabolite";
        }

        $hasil=mysqli_query($conn,$query);
        while ($row = mysqli_fetch_array($hasil, MYSQLI_ASSOC)) {
            $tables[]=$row;
        }
        echo '<table class="table table-striped table-bordered table-hover"><tr>';
        foreach ($tables[0] as $key => $value)
        {
            echo "<th>{$key}</th>";
            $keys[] = $key;
        }
        echo '</tr>';

        $hasil=mysqli_query($conn,$sql);
        $no=0;
        if ($_POST['kolom']=="Species") {
        while ($data = mysqli_fetch_array($hasil)) {
            $no++;
            ?>
            <tbody>
            <tr>
              <!--<td><?=$no++?></td>-->
              <td><?=$data['Species']?></td>
              <td><?=$data['Habitat']?></td>
              <td><?=$data['Location']?></td>
              <td><img src="../images/<?=$data['Spectrum']?>"></td>
              <td><?=$data['Metabolite']?></td>
              <td><?=$data['Quantity']?></td>
            </tr>
            </tbody>
            <?php
        }
      } else {
        while ($data = mysqli_fetch_array($hasil)) {
            $no++;
            ?>
            <tbody>
            <tr>
              <!--<td><?=$no++?></td>-->
              <td><?=$data['Metabolite']?></td>
              <td><?=$data['Detail']?></td>
              <td><img src="../images/<?=$data['Structure']?>"></td>
              <td><?=$data['Quantity']?></td>
              <td><?=$data['Species']?></td>
            </tr>
            </tbody>
            <?php
          }
        }

      }
      ?>

</div>

</body>
</html>
