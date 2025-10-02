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
?>

<div class="box">
    <h1>Species</h1>
    <h4>
        <small>Data Species</small>
        <div class="pull-right">
            <a href="data.php" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-refresh"></i></a>
            <a href="add.php" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-plus"></i>Tambah Species</a>
        </div>
    </h4>
    <div style="margin-bottom: 20px; ">
        <form class="form-inline" action="" method="post">
            <div class="form-group">
                <input type="text" name="pencarian" class="form-control" placeholder="Pencarian Species" 
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
                    <th><i class="glyphicon glyphicon-cog"></i></th>
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
            $no = $posisi + 1; // Start counting from the correct position

            $pencarian = @trim($_POST['pencarian']);

            // --- SQL Injection Fix: Prepare statements based on search context ---
            if ($_SERVER['REQUEST_METHOD'] == "POST" && $pencarian != '') {
                
                // 1. Prepare search query (uses prepared statements for security)
                $search_term = "%" . $pencarian . "%";
                $sql = "SELECT * FROM species WHERE species_name LIKE ? OR habitat LIKE ? OR location LIKE ? LIMIT ?, ?";
                $query = $sql;
                
                // For counting total rows (without LIMIT)
                $queryJml = "SELECT * FROM species WHERE species_name LIKE ? OR habitat LIKE ? OR location LIKE ?";
                
                // Execute the search query
                $stmt = $conn->prepare($query);
                if (!$stmt) { die('Prepare search failed: ' . $conn->error); }
                // sssssii: 3 strings for LIKE, 2 integers for LIMIT (must be bound)
                $stmt->bind_param("sssii", $search_term, $search_term, $search_term, $posisi, $batas); 
                
            } else {
                // 1. Prepare default query (uses prepared statements for security)
                $query = "SELECT * FROM species LIMIT ?, ?";
                $queryJml = "SELECT * FROM species";
                
                // Execute the default query
                $stmt = $conn->prepare($query);
                if (!$stmt) { die('Prepare default failed: ' . $conn->error); }
                // ii: 2 integers for LIMIT
                $stmt->bind_param("ii", $posisi, $batas);
            }
            
            $stmt->execute();
            $sql_species = $stmt->get_result();
            // --- End of SQL Injection Fix ---

            if($sql_species->num_rows > 0) {
                while($data = $sql_species->fetch_assoc()) { ?>
                    <tr>
                        <td><?=$no++?></td>
                        <td><?=secure_output($data['species_name'])?></td>
                        <td><?=secure_output($data['habitat'])?></td>
                        <td><?=secure_output($data['location'])?></td>
                        <td><img src="../images/<?=secure_output($data['spectrum'])?>" alt="Spectrum Image"></td>
                        <td class="text-center">
                            <a href="edit.php?id=<?=secure_output($data['species_id'])?>" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                            <a href="delete.php?id=<?=secure_output($data['species_id'])?>" onclick="return confirm('Yakin data akan dihapus?')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></a>
                        </td>
                    </tr>
                <?php
                }
            } else {
                echo "<tr><td colspan=\"6\" align=\"center\">Data tidak ditemukan</td></tr>";
            }
            
            $stmt->close();
            ?>
            </tbody>
        </table>
    </div>

    <?php
    $pencarian_status = isset($_POST['pencarian']) ? trim($_POST['pencarian']) : '';

    // --- Execute queryJml securely for total count ---
    $jml = 0;
    
    if ($pencarian_status == '') {
        // Count for full list
        $resultJml = mysqli_query($conn, "SELECT COUNT(*) FROM species");
        $rowJml = mysqli_fetch_row($resultJml);
        $jml = $rowJml[0];
        
        echo "<div style=\"float: left;\">Jumlah Data : <b>$jml</b></div>";
    } else {
        // Count for search results (Prepared Statement)
        $search_term = "%" . $pencarian_status . "%";
        $stmtJml = $conn->prepare("SELECT COUNT(*) FROM species WHERE species_name LIKE ? OR habitat LIKE ? OR location LIKE ?");
        if (!$stmtJml) { die('Prepare count failed: ' . $conn->error); }
        $stmtJml->bind_param("sss", $search_term, $search_term, $search_term);
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