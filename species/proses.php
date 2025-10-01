<?php
require_once "../_config/config.php";

if(isset($_POST['add'])) {
    $nama = trim(mysqli_real_escape_string($conn, $_POST['nama']));
    $habitat = trim(mysqli_real_escape_string($conn, $_POST['habitat']));
    $location = trim(mysqli_real_escape_string($conn, $_POST['location']));
    $spectrum = trim(mysqli_real_escape_string($conn, $_POST['spectrum']));
    $username = $_SESSION['username'];

    // upload gambar
    $spectrum = upload();

    if(!$spectrum) {
      echo "<script>
              alert('Gagal mengunggah gambar!')
            </script>";
      echo "<script>window.location='add.php';</script>";
      return false;
    }

    // cek Nama species sudah ada atau belum
    $result = mysqli_query($conn, "SELECT species_name FROM species WHERE
      species_name = '$nama'");

   if ( mysqli_fetch_assoc($result) ) {
     echo "<script>
             alert('Data species dengan nama tersebut sudah terdaftar!')
           </script>";
     echo "<script>window.location='data.php';</script>";

     return false;
   }

    $tambah = mysqli_query($conn, "INSERT INTO species VALUES (NULL, '$nama', '$habitat', '$location', '$spectrum', NULL, '$username')") or die (mysqli_error($conn));

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
    $spectrumLama = trim(mysqli_real_escape_string($conn, $_POST['spectrumLama']));
    $speciesLama = trim(mysqli_real_escape_string($conn, $_POST['speciesLama']));
    $nama = trim(mysqli_real_escape_string($conn, $_POST['nama']));
    $habitat = trim(mysqli_real_escape_string($conn, $_POST['habitat']));
    $location = trim(mysqli_real_escape_string($conn, $_POST['location']));
    $username = $_SESSION['username'];

    //cek apakah user pilih gambar baru atau tidak
    if($_FILES['spectrum']['error'] === 4) {
        $spectrum = $spectrumLama;
    } else {
        $spectrum = upload();
    }

    //cek apakah user merubah species atau tidak
    if($_POST['nama'] != $speciesLama) {
        // cek Nama species sudah ada atau belum
        $result = mysqli_query($conn, "SELECT species_name FROM species WHERE
          species_name = '$nama'");

         if ( mysqli_fetch_assoc($result) ) {
           echo "<script>
                   alert('Data species dengan nama tersebut sudah terdaftar!')
                 </script>";
           echo "<script>window.location='data.php';</script>";

           return false;

        } else {
              mysqli_query($conn, "UPDATE species SET
                                      species_name = '$nama',
                                      habitat = '$habitat',
                                      location = '$location',
                                      spectrum = '$spectrum',
                                      username = '$username'
                                      WHERE species_id = '$id'") or die (mysqli_error($conn));
              echo "<script>window.location='data.php';</script>";
        }

      } else {
        mysqli_query($conn, "UPDATE species SET
                                species_name = '$nama',
                                habitat = '$habitat',
                                location = '$location',
                                spectrum = '$spectrum',
                                username = '$username'
                                WHERE species_id = '$id'") or die (mysqli_error($conn));
        echo "<script>window.location='data.php';</script>";

      }
  }


function upload() {
    $namaFile = $_FILES['spectrum']['name'];
    $ukuranFile = $_FILES['spectrum']['size'];
    $error = $_FILES['spectrum']['error'];
    $tmpName = $_FILES['spectrum']['tmp_name'];

    if($error === 4) {
        echo "<script>
                alert('pilih gambar terlebih dahulu!')
              </script>";
          return false;
    }


// cek apakah yang diupload adalah gambar
$ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
$ekstensiGambar = explode('.', $namaFile);
$ekstensiGambar = strtolower(end($ekstensiGambar));
if(!in_array($ekstensiGambar, $ekstensiGambarValid)) {
    echo "<script>
            alert('File yang anda upload bukan gambar!')
          </script>";
      return false;
}

// cek jika ukuran terlalu besar
if($ukuranFile > 1000000) {
    echo "<script>
            alert('Ukuran file terlalu besar!')
          </script>";
      return false;
}

//lolos pengecekan, gambar siap diupload
//generate nama gambar baru
$namaFileBaru = uniqid();
$namaFileBaru .= '.';
$namaFileBaru .= $ekstensiGambar;

move_uploaded_file($tmpName, '../images/' . $namaFileBaru);
return $namaFileBaru;
}

 ?>
