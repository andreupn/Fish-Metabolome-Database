<?php 
// Assuming _header.php includes necessary files and sets up the $conn connection
include_once('../_header.php'); 

// Ensure $conn is available
if (!isset($conn)) {
    die("Error: Database connection not available.");
}

// Function to securely output data (XSS prevention)
function secure_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Base query for JOINs
$base_query = "FROM fmdb 
               INNER JOIN species ON fmdb.species_id = species.species_id 
               INNER JOIN metabolite ON fmdb.metabolite_id = metabolite.metabolite_id";

// Search condition (for WHERE clause)
$search_condition = "species_name LIKE ? OR habitat LIKE ? OR location LIKE ? OR metabolite_name LIKE ? OR detail LIKE ? OR quantity LIKE ?";
?>

<div class="box">
    <h1>FMDB Data</h1>
    <h4>
        <small>Data Kompilasi</small>
        <div class="pull-right">
            <a href="data_kompilasi.php" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-refresh"></i></a>
        </div>
    </h4>
    <div style="margin-bottom: 20px; ">
        <form class="form-inline" action="" method="post">
            <div class="form-group">
                <input type="text" name="pencarian" class="form-control" placeholder="Masukan kata kunci!"
                       value="<?= secure_output(@$_POST['pencarian']) ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hiden="true"></span></button>
            </div>
        </form>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Species</th>
                    <th>Habitat</th>
                    <th>Location</th>
                    <th>Spectrum</th>
                    <th>Metabolite</th>
                    <th>Detail</th>
                    <th>Structure</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $batas = 5;
            $hal = @$_GET['hal'];
            if(empty($hal)) {
                $posisi = 0;
                $hal = 1;
            } else {
                $posisi = ($hal - 1) * $batas;
            }
            $no = $posisi + 1;

            $pencarian = @trim($_POST['pencarian']);

            // --- SQL Injection Fix: Use Prepared Statements ---
            if ($_SERVER['REQUEST_METHOD'] == "POST" && $pencarian != '') {
                
                // 1. Setup search query
                $search_term = "%" . $pencarian . "%";
                $query = "SELECT * " . $base_query . " WHERE " . $search_condition . " LIMIT ?, ?";
                
                // Execute the search query
                $stmt = $conn->prepare($query);
                if (!$stmt) { die('Prepare search failed: ' . $conn->error); }
                // ssssssii: 6 strings for LIKE terms, 2 integers for LIMIT
                $stmt->bind_param("ssssssii", $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $posisi, $batas);
                
                // Query for total rows in search results (no LIMIT)
                $queryJml_base = "SELECT COUNT(*) " . $base_query . " WHERE " . $search_condition;
                
            } else {
                // 1. Setup default query (no search)
                $query = "SELECT * " . $base_query . " LIMIT ?, ?";
                $queryJml_base = "SELECT COUNT(*) " . $base_query;
                
                // Execute the default query
                $stmt = $conn->prepare($query);
                if (!$stmt) { die('Prepare default failed: ' . $conn->error); }
                // ii: 2 integers for LIMIT
                $stmt->bind_param("ii", $posisi, $batas);
            }
            
            $stmt->execute();
            $sql_quantity = $stmt->get_result();
            // --- End of SQL Injection Fix ---

            if($sql_quantity->num_rows > 0) {
                while ($data = $sql_quantity->fetch_assoc()) { ?>
                    <tr>
                        <td><?=$no++?>.</td>
                        <td><?=secure_output($data['species_name'])?></td>
                        <td><?=secure_output($data['habitat'])?></td>
                        <td><?=secure_output($data['location'])?></td>
                        <td><img src="../images/<?=secure_output($data['spectrum'])?>" alt="Spectrum"></td>
                        <td><?=secure_output($data['metabolite_name'])?></td>
                        <td><?=secure_output($data['detail'])?></td>
                        <td><img src="../images/<?=secure_output($data['structure'])?>" alt="Structure"></td>
                        <td><?=secure_output($data['quantity'])?></td>
                    </tr>
                <?php
                }
            } else {
                echo "<tr><td colspan=\"9\" align=\"center\">Data tidak ditemukan</td></tr>";
            }
            
            $stmt->close();
            ?>
            </tbody>
        </table>
    </div>

    <?php
    $pencarian_status = @trim($_POST['pencarian']);
    $jml = 0;

    // --- Execute COUNT query securely for total count ---
    if ($pencarian_status == '') {
        // Count for full list (Simple COUNT)
        $resultJml = mysqli_query($conn, $queryJml_base);
        $rowJml = mysqli_fetch_row($resultJml);
        $jml = $rowJml[0];
        
        echo "<div style=\"float: left;\">Jumlah Data : <b>$jml</b></div>";
    } else {
        // Count for search results (Prepared Statement)
        $search_term = "%" . $pencarian_status . "%";
        $stmtJml = $conn->prepare($queryJml_base);
        if (!$stmtJml) { die('Prepare count failed: ' . $conn->error); }
        // ssssss: 6 strings for LIKE terms
        $stmtJml->bind_param("ssssss", $search_term, $search_term, $search_term, $search_term, $search_term, $search_term);
        $stmtJml->execute();
        $resultJml = $stmtJml->get_result();
        $rowJml = $resultJml->fetch_row();
        $jml = $rowJml[0];
        $stmtJml->close();
        
        echo "<div style=\"float:left;\">Data Hasil Pencarian : <b>$jml</b></div>";
    }
    // --- End of count execution ---

    if($pencarian_status == '') { ?>
        <div style="float:right;">
            <ul class="pagination pagination-sm" style="margin:0">
                <?php
                $jml_hal = ceil($jml / $batas);
                for ($i=1; $i <= $jml_hal; $i++) {
                    if($i != $hal) {
                        echo "<li><a href=\"?hal=$i\">$i</a></li>";
                    } else {
                        echo "<li class=\"active\"><a>$i</a></li>";
                    }
                }
                ?>
            </ul>
        </div>
    <?php } ?>
</div>

<?php include_once('../_footer.php'); ?>