<?php

// Assuming _header.php includes necessary files and sets up the $conn connection
include_once('../_header.php');

// Ensure $conn is available from _header.php
if (!isset($conn)) {
    die("Error: Database connection not available.");
}

// 1. **SQL Injection Fix:** Use Prepared Statements to fetch data
$id = @$_GET['id'];

// Prepare the statement with a placeholder (?)
$stmt = $conn->prepare("SELECT * FROM species WHERE species_id = ?");

// Check if the prepare statement failed
if (!$stmt) {
    die('Prepare failed: (' . $conn->errno . ') ' . $conn->error);
}

// Bind the parameter: 'i' indicates an integer type for species_id
$stmt->bind_param("i", $id);

// Execute the statement
$stmt->execute();

// Get the result set
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Check if data was found
if (!$data) {
    // Handle case where no species found for the given ID
    echo "<div class='alert alert-danger'>Data species tidak ditemukan.</div>";
    // Optionally redirect: header('Location: data.php');
    include_once('../_footer.php');
    exit;
}

// Close the statement
$stmt->close();

// 2. **XSS Fix:** Define a function to securely output data
function secure_output($value) {
    // Use ENT_QUOTES to convert both single and double quotes
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html>
    <style>
        input[type=file] {
        width: 500px;
        max-width: 100%;
        color: #444;
        padding: 5px;
        background: #fff;
        border-radius: 3px;
        border: 1px solid grey ;
        }
    </style>
</html>

    <div class="box">
        <h1>Species</h1>
        <h4>
            <small>Edit Data Species</small>
            <div class="pull-right">
                <a href="data.php" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-chevron-left"></i>Kembali</a>
            </div>
        </h4>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <form action="proses.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama Species</label>
                        <input type="hidden" name="id" value="<?= secure_output($data['species_id']) ?>">
                        <input type="hidden" name="spectrumLama" value="<?= secure_output($data['spectrum']) ?>">
                        <input type="hidden" name="speciesLama" value="<?= secure_output($data['species_name']) ?>">
                        <input type="text" name="nama" id="nama" value="<?= secure_output($data['species_name']) ?>" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="habitat">Habitat</label>
                        <input type="text" name="habitat" id="habitat" value="<?= secure_output($data['habitat']) ?>" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" value="<?= secure_output($data['location']) ?>" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="spectrum">Spectrum</label>
                        <img src="../images/<?= secure_output($data['spectrum']) ?>"> <br>
                        <input type="file" name="spectrum" id="spectrum" class="">
                    </div>
                    <div class="form-group pull-right">
                        <input type="submit" name="edit" value="Simpan" class="btn btn-success">
                    </div>
                </form>

            </div>

        </div>
    </div>

    <?php include_once('../_footer.php'); ?>