<?php

// koneksi ke databse
$conn = mysqli_connect("localhost", "root", "", "fmdb");



function query($query) {
  global $conn;
  $result = mysqli_query($conn, $query);
  $rows = [];
  while( $row = mysqli_fetch_assoc($result) ) {
    $rows[] = $row;
  }
  return $rows;

}

function registrasi($data) {
    global $conn;

    $Nama = ($data["nama"]);
    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

     // cek username sudah ada atau belum
     $result = mysqli_query($conn, "SELECT username FROM curator WHERE
       username = '$username'");

    if ( mysqli_fetch_assoc($result) ) {
      echo "<script>
              alert('username sudah terdaftar!')
            </script>";
      return false;
    }


    //cek Konfirmasi Password
    if( $password !== $password2 ) {
      echo "<script>
              alert('Konfirmasi password tidak sesuai!');
            </script>";
      return false;
    }

    // enkripsi password2
    $password = password_hash($password, PASSWORD_DEFAULT);

    // tambahkan userbaru ke databasee

    mysqli_query($conn, "INSERT INTO curator VALUES('nama', '$username',
      '$password')");

    return mysqli_affected_rows($conn);
}

?>
