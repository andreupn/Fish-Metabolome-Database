<?php
require_once "../_config/config.php";

if(isset($_POST['add'])) {

    $species = trim(mysqli_real_escape_string($conn, $_POST['species']));
    $metabolite = trim(mysqli_real_escape_string($conn, $_POST['metabolite']));
    $quantity = trim(mysqli_real_escape_string($conn, $_POST['quantity']));
    $username = $_SESSION['username'];

    // cek apakah quantity dengan kombinasi species dan metabolite sudah ada atau belum
    $result = mysqli_query($conn, "SELECT species_id FROM fmdb WHERE
      species_id = '$species' AND metabolite_id = $metabolite");

   if ( mysqli_fetch_assoc($result) ) {
     echo "<script>
             alert('Data quantity dengan nama species dan metabolite tersebut sudah terdaftar!')
           </script>";
     echo "<script>window.location='data.php';</script>";

     return false;
   }

    $tambah = mysqli_query($conn, "INSERT INTO fmdb VALUES (NULL, '$species', '$metabolite', '$quantity', NULL, '$username')") or die (mysqli_error($conn));

    // cek apakah data berhasil ditambahkan atau tidak
    if ($tambah > 0) {
      echo "
            <script>
                alert('Data berhasil ditambahkan');
                window.location='data.php';
            </script>
            ";
    } else {
      echo "
            <script>
                alert('Data gagal ditambahkan');
                window.location='data.php';
            </script>
            ";
    }


} else if(isset($_POST['edit'])) {

    $id = $_POST['id'];
    $speciesIdLama = trim(mysqli_real_escape_string($conn, $_POST['speciesIdLama']));
    $metaboliteIdLama = trim(mysqli_real_escape_string($conn, $_POST['metaboliteIdLama']));
    $species = trim(mysqli_real_escape_string($conn, $_POST['species']));
    $metabolite = trim(mysqli_real_escape_string($conn, $_POST['metabolite']));
    $quantity = trim(mysqli_real_escape_string($conn, $_POST['quantity']));
    $username = $_SESSION['username'];

    //cek apakah user merubah species dan metabolite atau tidak
    if( ($_POST['species'] != $speciesIdLama) || ($_POST['metabolite'] != $metaboliteIdLama) ) {

        // cek apakah data quantity dengan kombinasi species dan metabolite sudah ada atau belum
        $result = mysqli_query($conn, "SELECT species_id FROM fmdb WHERE
          species_id = '$species' AND metabolite_id = $metabolite");

         if ( mysqli_fetch_assoc($result) ) {
           echo "<script>
                   alert('Data quantity dengan nama species dan metabolite tersebut sudah terdaftar!')
                 </script>";
           echo "<script>window.location='data.php';</script>";

           return false;

         } else {
               mysqli_query($conn, "UPDATE fmdb SET
                                       species_id = '$species',
                                       metabolite_id = '$metabolite',
                                       quantity = '$quantity',
                                       username = '$username'
                                       WHERE fmdb_id = '$id'") or die (mysqli_error($conn));
               echo "<script>window.location='data.php';</script>";
          }
    } else {
          mysqli_query($conn, "UPDATE fmdb SET
                                  species_id = '$species',
                                  metabolite_id = '$metabolite',
                                  quantity = '$quantity',
                                  username = '$username'
                                  WHERE fmdb_id = '$id'") or die (mysqli_error($conn));
          echo "<script>window.location='data.php';</script>";
    }

  }


?>
