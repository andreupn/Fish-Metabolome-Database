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


if(isset($_POST['add'])) {
    
    // --- CSRF VALIDATION (Check before proceeding) ---
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error: Invalid CSRF token. Request blocked.");
    }
    unset($_SESSION['csrf_token']);
    // -------------------------------------------------

    // Inputs are NOT escaped, as Prepared Statements will handle it
    $species = trim($_POST['species']); // species_id
    $metabolite = trim($_POST['metabolite']); // metabolite_id
    $quantity = trim($_POST['quantity']);
    $username = $_SESSION['username'];

    // 1. Check for existing combination (Prepared Statement)
    // Assuming species_id and metabolite_id are integers 'i'
    $stmt_check = $conn->prepare("SELECT fmdb_id FROM fmdb WHERE species_id = ? AND metabolite_id = ?");
    if (!$stmt_check) {
         die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
    }
    $stmt_check->bind_param("ii", $species, $metabolite);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $alert_msg = secure_alert_output("Data quantity dengan kombinasi species dan metabolite tersebut sudah terdaftar!");
        echo "<script>alert('{$alert_msg}')</script>";
        echo "<script>window.location='data.php';</script>";
        $stmt_check->close();
        return false;
    }
    $stmt_check->close();

    // 2. Insert new data (Prepared Statement)
    // Assuming the database autogenerates the first column (NULL in original code)
    // Assuming species_id and metabolite_id are integers 'i', and quantity is a string 's'
    $sql_insert = "INSERT INTO fmdb (species_id, metabolite_id, quantity, username) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    
    if (!$stmt_insert) {
         die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
    }
    
    // fmdb_id is autoincremented, hence not bound
    $stmt_insert->bind_param("iiss", $species, $metabolite, $quantity, $username);
    $success = $stmt_insert->execute();
    $stmt_insert->close();
    
    // cek apakah data berhasil ditambahkan atau tidak
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
    $id = $_POST['id']; // fmdb_id - CRITICALLY DANGEROUS IN ORIGINAL CODE
    $speciesIdLama = trim($_POST['speciesIdLama']);
    $metaboliteIdLama = trim($_POST['metaboliteIdLama']);
    $species = trim($_POST['species']);
    $metabolite = trim($_POST['metabolite']);
    $quantity = trim($_POST['quantity']);
    $username = $_SESSION['username'];

    //cek apakah user merubah species dan metabolite atau tidak
    if( ($_POST['species'] != $speciesIdLama) || ($_POST['metabolite'] != $metaboliteIdLama) ) {

        // 3. Check for existing combination (Prepared Statement)
        $stmt_check = $conn->prepare("SELECT fmdb_id FROM fmdb WHERE species_id = ? AND metabolite_id = ? AND fmdb_id != ?");

        if (!$stmt_check) {
             die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
        }
        // ii: species_id, metabolite_id (new values); i: fmdb_id (current record)
        $stmt_check->bind_param("iii", $species, $metabolite, $id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $alert_msg = secure_alert_output("Data quantity dengan kombinasi species dan metabolite tersebut sudah terdaftar!");
            echo "<script>alert('{$alert_msg}')</script>";
            echo "<script>window.location='data.php';</script>";
            $stmt_check->close();
            return false;
        }
        $stmt_check->close();
    }
    
    // 4. Update data (Prepared Statement - SECURELY using $id)
    $sql_update = "UPDATE fmdb SET
                     species_id = ?,
                     metabolite_id = ?,
                     quantity = ?,
                     username = ?
                     WHERE fmdb_id = ?";
    
    $stmt_update = $conn->prepare($sql_update);
    
    if (!$stmt_update) {
        die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
    }
    
    // iisi: species_id, metabolite_id (integers); s: quantity, username (strings); i: fmdb_id (integer)
    $stmt_update->bind_param("iissi", $species, $metabolite, $quantity, $username, $id);
    $stmt_update->execute();
    $stmt_update->close();
    
    echo "<script>window.location='data.php';</script>";
}

?>