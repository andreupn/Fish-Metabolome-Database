<?php
// Note: Assuming '../_config/config.php' establishes the $conn connection variable.
require_once "../_config/config.php";

// Set a default value for $conn if the config file doesn't set it (good practice)
if (!isset($conn)) {
    die("Database connection variable not available.");
}

// Function to handle file uploads
function upload() {
    // ... (Your existing upload function with minor adjustments below)
    
    // --- Security Enhancement: Add MIME Type Check ---
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $_FILES['spectrum']['tmp_name']);
    finfo_close($finfo);

    $allowed_mimes = ['image/jpeg', 'image/png'];
    
    if(!in_array($mime_type, $allowed_mimes)) {
        echo "<script>
                  alert('Tipe file yang diunggah tidak valid (harus JPG/PNG)!')
              </script>";
        return false;
    }

    // ... (Rest of your existing upload function remains)
    $namaFile = $_FILES['spectrum']['name'];
    $ukuranFile = $_FILES['spectrum']['size'];
    $error = $_FILES['spectrum']['error'];
    $tmpName = $_FILES['spectrum']['tmp_name'];

    // 1. Check for no file selected
    if($error === 4) {
        echo "<script>
                  alert('pilih gambar terlebih dahulu!')
              </script>";
        return false;
    }

    // 2. Check file extension (redundant but kept for strictness)
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if(!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>
                  alert('File yang anda upload bukan gambar!')
              </script>";
        return false;
    }

    // 3. Check file size
    if($ukuranFile > 1000000) { // 1MB limit
        echo "<script>
                  alert('Ukuran file terlalu besar!')
              </script>";
        return false;
    }

    // Lolos pengecekan, gambar siap diupload
    // Generate nama gambar baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    // IMPORTANT: Ensure the '../images/' directory has correct write permissions
    move_uploaded_file($tmpName, '../images/' . $namaFileBaru);
    return $namaFileBaru;
}


if(isset($_POST['add'])) {
    
    // 1. Inputs are not escaped with mysqli_real_escape_string() anymore
    //    because Prepared Statements handle it.
    $nama = trim($_POST['nama']);
    $habitat = trim($_POST['habitat']);
    $location = trim($_POST['location']);
    // $spectrum is overwritten by the upload function return value
    $username = $_SESSION['username'];

    // 2. Upload the file
    $spectrum = upload();

    if(!$spectrum) {
        // Error message already displayed in upload()
        echo "<script>window.location='add.php';</script>";
        return false;
    }

    // 3. Check for existing species name (Using Prepared Statements)
    $stmt_check = $conn->prepare("SELECT species_name FROM species WHERE species_name = ?");
    if (!$stmt_check) {
         die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
    }
    $stmt_check->bind_param("s", $nama);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Sanitize $nama for safe output in JavaScript alert (XSS prevention)
        $safe_nama = htmlspecialchars($nama, ENT_QUOTES, 'UTF-8');
        echo "<script>
                  alert('Data species dengan nama \"{$safe_nama}\" sudah terdaftar!')
              </script>";
        echo "<script>window.location='data.php';</script>";
        $stmt_check->close();
        return false;
    }
    $stmt_check->close();
    
    // 4. Insert new species (Using Prepared Statements)
    $sql_insert = "INSERT INTO species (species_name, habitat, location, spectrum, username) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    
    if (!$stmt_insert) {
         die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
    }
    
    // 's' s s s s' represents the data types of the 5 parameters (all strings)
    $stmt_insert->bind_param("sssss", $nama, $habitat, $location, $spectrum, $username);
    $success = $stmt_insert->execute();
    $stmt_insert->close();
    
    // cek apakah data berhasil ditambahkan atau tidak
    if ($success) {
        echo "<script>
                  alert('Data berhasil ditambahkan');
                  window.location='data.php';
              </script>";
    } else {
        echo "<script>
                  alert('Data gagal ditambahkan');
                  window.location='data.php';
              </script>";
    }

} else if(isset($_POST['edit'])) {
    
    // 1. Inputs are not escaped with mysqli_real_escape_string() anymore.
    //    $id is now handled securely by the prepared statement.
    $id = $_POST['id'];
    $spectrumLama = trim($_POST['spectrumLama']);
    $speciesLama = trim($_POST['speciesLama']);
    $nama = trim($_POST['nama']);
    $habitat = trim($_POST['habitat']);
    $location = trim($_POST['location']);
    $username = $_SESSION['username'];

    // 2. Handle image upload/retention
    if($_FILES['spectrum']['error'] === 4) {
        $spectrum = $spectrumLama;
    } else {
        $spectrum = upload();
        if(!$spectrum) {
            echo "<script>window.location='edit.php?id={$id}';</script>";
            return false;
        }
        // OPTIONAL: Add logic here to delete the old file if upload was successful.
    }

    $is_name_changed = ($_POST['nama'] != $speciesLama);

    if ($is_name_changed) {
        // 3. Check if new species name already exists (Prepared Statement)
        $stmt_check = $conn->prepare("SELECT species_name FROM species WHERE species_name = ? AND species_id != ?");
        if (!$stmt_check) {
             die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
        }
        // The second parameter 'i' is for integer, assuming species_id is an INT/BIGINT
        $stmt_check->bind_param("si", $nama, $id); 
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $safe_nama = htmlspecialchars($nama, ENT_QUOTES, 'UTF-8');
            echo "<script>
                    alert('Data species dengan nama \"{$safe_nama}\" sudah terdaftar!')
                  </script>";
            echo "<script>window.location='data.php';</script>";
            $stmt_check->close();
            return false;
        }
        $stmt_check->close();
    }
    
    // 4. Update species (Prepared Statement - SECURELY using $id)
    $sql_update = "UPDATE species SET 
                     species_name = ?,
                     habitat = ?,
                     location = ?,
                     spectrum = ?,
                     username = ?
                   WHERE species_id = ?";
    
    $stmt_update = $conn->prepare($sql_update);
    
    if (!$stmt_update) {
        die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
    }
    
    // 'sssssi' represents the data types: 5 strings, 1 integer ($id)
    $stmt_update->bind_param("sssssi", $nama, $habitat, $location, $spectrum, $username, $id);
    $stmt_update->execute();
    $stmt_update->close();
    
    echo "<script>window.location='data.php';</script>";
}

?>