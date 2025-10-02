<?php
require_once "../_config/config.php";

// Ensure $conn is available from the config file
if (!isset($conn)) {
    die("Error: Database connection not available.");
}

// 1. Get the ID
$id = $_GET['id'];

// 2. Use a Prepared Statement for the DELETE query (Prevents SQL Injection)
$sql_delete = "DELETE FROM species WHERE species_id = ?";
$stmt = $conn->prepare($sql_delete);

// Check if the prepare statement failed
if (!$stmt) {
    die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
}

// Bind the parameter: 'i' indicates an integer type for species_id
// This is the CRITICAL step that prevents SQL Injection
$stmt->bind_param("i", $id);

// Execute the statement
$success = $stmt->execute();
$stmt->close();

// Check if the deletion was successful before redirecting
if ($success) {
    echo "
        <script>
            alert('Data berhasil dihapus');
            window.location='data.php';
        </script>
        ";
} else {
    // This branch is rarely hit if you use die() on statement errors, but good for logic.
    echo "
        <script>
            alert('Data gagal dihapus');
            window.location='data.php';
        </script>
        ";
}
?>