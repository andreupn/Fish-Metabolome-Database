<?php 
include_once('../_header.php'); 

// Ensure $conn is available
if (!isset($conn)) {
    die("Error: Database connection not available.");
}

// --- SECURITY FIX: Generate and store a CSRF token ---
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Function to securely output data (XSS prevention)
function secure_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// --- SQL Injection Fix: Use Prepared Statements to fetch data ---
$id = @$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM metabolite WHERE metabolite_id = ?");
if (!$stmt) { die('Prepare failed: (' . $conn->errno . ') ' . $conn->error); }
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "<div class='alert alert-danger'>Data metabolite tidak ditemukan.</div>";
    include_once('../_footer.php');
    exit;
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
        <h1>Metabolite</h1>
        <h4>
            <small>Edit Data Metabolite</small>
            <div class="pull-right">
                <a href="data.php" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-chevron-left"></i>Kembali</a>
            </div>
        </h4>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <form action="proses.php" method="post" enctype="multipart/form-data">
                    
                    <input type="hidden" name="csrf_token" value="<?= secure_output($csrf_token) ?>">
                    
                    <div class="form-group">
                        <label for="nama">Nama Metabolite</label>
                        <input type="hidden" name="id" value="<?= secure_output($data['metabolite_id']) ?>">
                        <input type="hidden" name="structureLama" value="<?= secure_output($data['structure']) ?>">
                        <input type="hidden" name="metaboliteLama" value="<?= secure_output($data['metabolite_name']) ?>">
                        <input type="text" name="nama" id="nama" value="<?= secure_output($data['metabolite_name']) ?>" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="detail">Detail</label>
                        <input type="text" name="detail" id="detail" value="<?= secure_output($data['detail']) ?>" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="structure">Structure</label> <br>
                        <img src="../images/<?= secure_output($data['structure']) ?>" alt="Metabolite Structure"> <br>
                        <input type="file" name="structure" id="structure" class="">
                    </div>
                    <div class="form-group pull-right">
                        <input type="submit" name="edit" value="Simpan" class="btn btn-success">
                    </div>
                </form>

            </div>

        </div>
    </div>

    <?php include_once('../_footer.php'); ?>