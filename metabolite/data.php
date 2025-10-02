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
    <h1>Metabolite</h1>
    <h4>
        <small>Data Metabolite</small>
        <div class="pull-right">
            <a href="data.php" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-refresh"></i></a>
            <a href="add.php" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-plus"></i>Tambah Metabolite</a>
        </div>
    </h4>
    <div style="margin-bottom: 20px; ">
        <form class="form-inline" action="" method="post">
            <div class="form-group">
                <input type="text" name="pencarian" class="form-control" placeholder="Pencarian Metabolite"
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
                    <th>Metabolite</th>
                    <th>Detail</th>
                    <th>Structure</th>
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
            $no = $posisi + 1;

            $pencarian = @trim($_POST['pencarian']);

            // --- SQL Injection Fix: Use Prepared Statements ---
            if ($_SERVER['REQUEST_METHOD'] == "POST" && $pencarian != '') {
                
                // 1. Setup search query
                $search_term = "%" . $pencarian . "%";
                $query = "SELECT * FROM metabolite WHERE metabolite_name LIKE ? OR detail LIKE ? LIMIT ?, ?";
                
                // Execute the search query
                $stmt = $conn->prepare($query);
                if (!$stmt) { die('Prepare search failed: ' . $conn->error); }
                // ssii: 2 strings for LIKE terms, 2 integers for LIMIT
                $stmt->bind_param("ssii", $search_term, $search_term, $posisi, $batas); 
                
                // Query for total rows in search results (no LIMIT)
                $queryJml_base = "SELECT COUNT(*) FROM metabolite WHERE metabolite_name LIKE ? OR detail LIKE ?";
                
            } else {
                // 1. Setup default query (no search)
                $query = "SELECT * FROM metabolite LIMIT ?, ?";
                $queryJml_base = "SELECT COUNT(*) FROM metabolite";
                
                // Execute the default query
                $stmt = $conn->prepare($query);
                if (!$stmt) { die('Prepare default failed: ' . $conn->error); }
                // ii: 2 integers for LIMIT
                $stmt->bind_param("ii", $posisi, $batas);
            }
            
            $stmt->execute();
            $sql_metabolite = $stmt->get_result();
            // --- End of SQL Injection Fix ---

            if($sql_metabolite->num_rows > 0) {
                while($data = $sql_metabolite->fetch_assoc()) { ?>
                    <tr>
                        <td><?=$no++?></td>
                        <td><?=secure_output($data['metabolite_name'])?></td>
                        <td><?=secure_output($data['detail'])?></td>
                        <td><img src="../images/<?=secure_output($data['structure'])?>" alt="Metabolite Structure"></td>
                        <td class="text-center">
                            <a href="edit.php?id=<?=secure_output($data['metabolite_id'])?>" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                            <a href="delete.php?id=<?=secure_output($data['metabolite_id'])?>" onclick="return confirm('Yakin data akan dihapus?')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></a>
                        </td>
                    </tr>
                <?php
                }
            } else {
                echo "<tr><td colspan=\"5\" align=\"center\">Data tidak ditemukan</td></tr>";
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
        // ss: 2 strings for LIKE terms
        $stmtJml->bind_param("ss", $search_term, $search_term);
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