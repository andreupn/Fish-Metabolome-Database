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

// --- SQL Injection Fix: Use Prepared Statements to fetch primary data (fmdb) ---
$id = @$_GET['id'];

$stmt_fmdb = $conn->prepare("SELECT fmdb_id, species_id, metabolite_id, quantity FROM fmdb WHERE fmdb_id = ?");
if (!$stmt_fmdb) { die('FMDB prepare failed: ' . $conn->error); }
$stmt_fmdb->bind_param("i", $id);
$stmt_fmdb->execute();
$result_fmdb = $stmt_fmdb->get_result();
$data = $result_fmdb->fetch_assoc();
$stmt_fmdb->close();

if (!$data) {
    // Handle case where fmdb data not found
    echo "<div class='alert alert-danger'>Data Quantity tidak ditemukan.</div>";
    include_once('../_footer.php');
    exit;
}

// Get the dependent IDs safely
$tmp_species_id = $data['species_id'];
$tmp_metabolite_id = $data['metabolite_id'];


// --- SQL Injection Fix: Fetch dependent species data securely ---
$stmt_species_tmp = $conn->prepare("SELECT species_name FROM species WHERE species_id = ?");
$stmt_species_tmp->bind_param("i", $tmp_species_id);
$stmt_species_tmp->execute();
$data_tmp_species = $stmt_species_tmp->get_result()->fetch_assoc();
$stmt_species_tmp->close();


// --- SQL Injection Fix: Fetch dependent metabolite data securely ---
$stmt_metabolite_tmp = $conn->prepare("SELECT metabolite_name FROM metabolite WHERE metabolite_id = ?");
$stmt_metabolite_tmp->bind_param("i", $tmp_metabolite_id);
$stmt_metabolite_tmp->execute();
$data_tmp_metabolite = $stmt_metabolite_tmp->get_result()->fetch_assoc();
$stmt_metabolite_tmp->close();
?>

    <div class="box">
        <h1>Metabolite Quantity</h1>
        <h4>
            <small>Edit Data Quantity Metabolite</small>
            <div class="pull-right">
                <a href="data.php" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-chevron-left"></i>Kembali</a>
            </div>
        </h4>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <form action="proses.php" method="post">
                    
                    <input type="hidden" name="csrf_token" value="<?= secure_output($csrf_token) ?>">
                    
                    <div class="form-group">
                        <label for="edit_species">Data Species Awal</label>
                        <input type="text" name="edit_species" id="edit_species" 
                               value="<?= secure_output($data_tmp_species['species_name']) ?>" class="form-control" disabled="disabled">
                               
                        <label for="species">Pilih Species Baru</label>
                        
                        <input type="hidden" name="id" value="<?= secure_output($data['fmdb_id']) ?>">
                        <input type="hidden" name="speciesIdLama" value="<?= secure_output($data['species_id']) ?>">
                        <input type="hidden" name="metaboliteIdLama" value="<?= secure_output($data['metabolite_id']) ?>">
                        
                        <select name="species" id="species" class="form-control" required>
                            <option value="">Pilih Species</option>
                            <?php
                            $sql_species = mysqli_query($conn, "SELECT species_id, species_name FROM species");
                            while($data_species = mysqli_fetch_array($sql_species)) {
                                $selected = ($data_species['species_id'] == $data['species_id']) ? 'selected' : '';
                                // XSS Fix: Apply secure_output()
                                echo '<option value="'.secure_output($data_species['species_id']).'" '.$selected.'>'.secure_output($data_species['species_name']).'</option>';
                            } ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_metabolite">Data Metabolite Awal</label>
                        <input type="text" name="edit_metabolite" id="edit_metabolite" 
                               value="<?= secure_output($data_tmp_metabolite['metabolite_name']) ?>" class="form-control" disabled="disabled">
                               
                        <label for="metabolite">Pilih Metabolite Baru</label>
                        <select name="metabolite" id="metabolite" class="form-control" required>
                            <option value="">Pilih Metabolite Baru</option>
                            <?php
                            $sql_metabolite = mysqli_query($conn, "SELECT metabolite_id, metabolite_name FROM metabolite");
                            while($data_metabolite = mysqli_fetch_array($sql_metabolite)) {
                                $selected = ($data_metabolite['metabolite_id'] == $data['metabolite_id']) ? 'selected' : '';
                                // XSS Fix: Apply secure_output()
                                echo '<option value="'.secure_output($data_metabolite['metabolite_id']).'" '.$selected.'>'.secure_output($data_metabolite['metabolite_name']).'</option>';
                            } ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity</label> <br>
                        <input type="text" name="quantity" id="quantity" value="<?= secure_output($data['quantity']) ?>" class="form-control" required autofocus>
                    </div>
                    
                    <div class="form-group pull-right">
                        <input type="submit" name="edit" value="Simpan" class="btn btn-success">
                    </div>
                </form>

            </div>

        </div>
    </div>

    <?php include_once('../_footer.php'); ?>