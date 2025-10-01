<?php
require_once "../_config/config.php";

if(isset($_POST['add'])) {

    $nama = trim(mysqli_real_escape_string($conn, $_POST['nama']));
    $detail = trim(mysqli_real_escape_string($conn, $_POST['detail']));
    $username = $_SESSION['username'];

    // upload gambar
    $structure = upload();

    if(!$structure) {
      echo "<script>
              alert('Gagal mengunggah gambar!')
            </script>";
      echo "<script>window.location='add.php';</script>";
      return false;
    }

    // cek Nama metabolite sudah ada atau belum
    $result = mysqli_query($conn, "SELECT metabolite_name FROM metabolite WHERE
      metabolite_name = '$nama'");

     if ( mysqli_fetch_assoc($result) ) {
       echo "<script>
               alert('Data metabolite dengan nama tersebut sudah terdaftar!')
             </script>";
       echo "<script>window.location='data.php';</script>";

       return false;
     }
    $tambah = mysqli_query($conn, "INSERT INTO metabolite VALUES (NULL, '$nama', '$detail', '$structure', NULL, '$username')") or die (mysqli_error($conn));

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
    $structureLama = trim(mysqli_real_escape_string($conn, $_POST['structureLama']));
    $metaboliteLama = trim(mysqli_real_escape_string($conn, $_POST['metaboliteLama']));
    $nama = trim(mysqli_real_escape_string($conn, $_POST['nama']));
    $detail = trim(mysqli_real_escape_string($conn, $_POST['detail']));
    $username = $_SESSION['username'];

    //cek apakah user pilih gambar baru atau tidak
    if($_FILES['structure']['error'] === 4) {
        $structure = $structureLama;
    } else {
        $structure = upload();
    }

    //cek apakah user merubah metabolite atau tidak
    if($_POST['nama'] != $metaboliteLama) {

        // cek Nama metabolite sudah ada atau belum
        $result = mysqli_query($conn, "SELECT metabolite_name FROM metabolite WHERE
          metabolite_name = '$nama'");

         if ( mysqli_fetch_assoc($result) ) {
           echo "<script>
                   alert('Data metabolite dengan nama tersebut sudah terdaftar!')
                 </script>";
           echo "<script>window.location='data.php';</script>";

           return false;

         } else {
               mysqli_query($conn, "UPDATE metabolite SET
                                       metabolite_name = '$nama',
                                       detail = '$detail',
                                       structure = '$structure',
                                       username = '$username'
                                       WHERE metabolite_id = '$id'") or die (mysqli_error($conn));
               echo "<script>window.location='data.php';</script>";
          }
      } else {
            mysqli_query($conn, "UPDATE metabolite SET
                                    metabolite_name = '$nama',
                                    detail = '$detail',
                                    structure = '$structure',
                                    username = '$username'
                                    WHERE metabolite_id = '$id'") or die (mysqli_error($conn));
            echo "<script>window.location='data.php';</script>";
      }
}


function upload() {
    $namaFile = $_FILES['structure']['name'];
    $ukuranFile = $_FILES['structure']['size'];
    $error = $_FILES['structure']['error'];
    $tmpName = $_FILES['structure']['tmp_name'];

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
