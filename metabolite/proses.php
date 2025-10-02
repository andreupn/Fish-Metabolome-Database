<?php
require_once "../_config/config.php";

if (!isset($conn)) {
    die("Database connection variable not available.");
}

// --- CRITICAL SECURITY STEP: Start session to access and validate the CSRF token ---
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// ----------------------------------------------------------------------------------

// Function for XSS prevention when displaying alerts
function secure_alert_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}


function upload($conn) {
    $namaFile = $_FILES['structure']['name'];
    $ukuranFile = $_FILES['structure']['size'];
    $error = $_FILES['structure']['error'];
    $tmpName = $_FILES['structure']['tmp_name'];

    // 1. Check for no file selected
    if($error === 4) {
        echo "<script>alert('pilih gambar terlebih dahulu!')</script>";
        return false;
    }

    // 2. Check file extension
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if(!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>alert('File yang anda upload bukan gambar!')</script>";
        return false;
    }
    
    // --- SECURITY ENHANCEMENT: MIME Type Check ---
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $tmpName);
    finfo_close($finfo);

    $allowed_mimes = ['image/jpeg', 'image/png'];
    if(!in_array($mime_type, $allowed_mimes)) {
        echo "<script>alert('Tipe file yang diunggah tidak valid (harus JPG/PNG)!')</script>";
        return false;
    }
    // ---------------------------------------------

    // 3. Check file size (1MB limit)
    if($ukuranFile > 1000000) {
        echo "<script>alert('Ukuran file terlalu besar!')</script>";
        return false;
    }

    // Generate unique filename
    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;

    // Move the uploaded file
    // IMPORTANT: Ensure the '../images/' directory does not allow script execution
    if (move_uploaded_file($tmpName, '../images/' . $namaFileBaru)) {
        return $namaFileBaru;
    } else {
        echo "<script>alert('Gagal memindahkan file!')</script>";
        return false;
    }
}


if(isset($_POST['add'])) {
    
    // --- CSRF VALIDATION (Check before proceeding) ---
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error: Invalid CSRF token. Request blocked.");
    }
    unset($_SESSION['csrf_token']);
    // -------------------------------------------------

    // Inputs are NOT escaped, as Prepared Statements will handle it
    $nama = trim($_POST['nama']);
    $detail = trim($_POST['detail']);
    $username = $_SESSION['username'];

    // 1. Upload the file
    $structure = upload($conn); // Pass $conn if needed, though not strictly required here
    if(!$structure) {
        echo "<script>window.location='add.php';</script>";
        return false;
    }

    // 2. Check for existing name (Prepared Statement)
    $stmt_check = $conn->prepare("SELECT metabolite_name FROM metabolite WHERE metabolite_name = ?");
    if (!$stmt_check) { die('Prepare failed: (' . $conn->errno . ') ' . $conn->error); }
    $stmt_check->bind_param("s", $nama);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $alert_msg = secure_alert_output("Data metabolite dengan nama tersebut sudah terdaftar!");
        echo "<script>alert('{$alert_msg}')</script>";
        echo "<script>window.location='data.php';</script>";
        $stmt_check->close();
        return false;
    }
    $stmt_check->close();
    
    // 3. Insert new data (Prepared Statement)
    $sql_insert = "INSERT INTO metabolite (metabolite_name, detail, structure, username) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    
    if (!$stmt_insert) { die('Prepare failed: (' . $conn->errno . ') ' . $conn->error); }
    
    $stmt_insert->bind_param("ssss", $nama, $detail, $structure, $username);
    $success = $stmt_insert->execute();
    $stmt_insert->close();
    
    if ($success) {
        echo "<script>alert('Data berhasil ditambahkan'); window.location='data.php';</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan'); window.location='data.php';</script>";
    }

} else if(isset($_POST['edit'])) {

    // --- CSRF VALIDATION (Check before proceeding) ---
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error: Invalid CSRF token. Request blocked.");
    }
    unset($_SESSION['csrf_token']);
    // -------------------------------------------------
    
    // Inputs are NOT escaped, as Prepared Statements will handle it
    $id = $_POST['id']; // metabolite_id
    $structureLama = trim($_POST['structureLama']);
    $metaboliteLama = trim($_POST['metaboliteLama']);
    $nama = trim($_POST['nama']);
    $detail = trim($_POST['detail']);
    $username = $_SESSION['username'];

    // 1. Handle image upload/retention
    if($_FILES['structure']['error'] === 4) {
        $structure = $structureLama;
    } else {
        $structure = upload($conn);
        if(!$structure) {
            echo "<script>window.location='edit.php?id={$id}';</script>";
            return false;
        }
        // OPTIONAL: Add logic here to delete the old file ($structureLama)
    }

    // 2. Check if name changed and if new name already exists (Prepared Statement)
    if($_POST['nama'] != $metaboliteLama) {

        $stmt_check = $conn->prepare("SELECT metabolite_name FROM metabolite WHERE metabolite_name = ? AND metabolite_id != ?");

        if (!$stmt_check) { die('Prepare failed: (' . $conn->errno . ') ' . $conn->error); }
        $stmt_check->bind_param("si", $nama, $id); 
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $alert_msg = secure_alert_output("Data metabolite dengan nama tersebut sudah terdaftar!");
            echo "<script>alert('{$alert_msg}')</script>";
            echo "<script>window.location='data.php';</script>";
            $stmt_check->close();
            return false;
        }
        $stmt_check->close();
    }
    
    // 3. Update data (Prepared Statement - SECURELY using $id)
    $sql_update = "UPDATE metabolite SET
                     metabolite_name = ?,
                     detail = ?,
                     structure = ?,
                     username = ?
                     WHERE metabolite_id = ?";
    
    $stmt_update = $conn->prepare($sql_update);
    
    if (!$stmt_update) { die('Prepare failed: (' . $conn->errno . ') ' . $conn->error); }
    
    // ssssi: 4 strings, 1 integer ($id)
    $stmt_update->bind_param("ssssi", $nama, $detail, $structure, $username, $id);
    $stmt_update->execute();
    $stmt_update->close();
    
    echo "<script>window.location='data.php';</script>";
}

?>