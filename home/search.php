<?php 
// Ensure session is started if needed for base_url context
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "../_config/config.php";

// Ensure $conn is available
if (!isset($conn)) {
    die("Error: Database connection not available.");
}

// Function to securely output data (XSS prevention)
function secure_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Function to securely output URLs or string data
function secure_url_output($url) {
    return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
}

// Initialize variables
$searchTerm = "";
$showTable = false;
$Species = "";
$Metabolite = "";
$kata_kunci = "";
$kolom = "";
$sql = "";
$query = "";

// 1. Process and Sanitize Input
if (isset($_POST['kata_kunci'])) {
    $showTable = true;
    
    // CRITICAL: Inputs are sanitized for display/variable safety, but NOT for SQL here.
    $kata_kunci = trim($_POST['kata_kunci']);
    
    if (isset($_POST['kolom'])) {
        $kolom = $_POST['kolom'];
        if ($kolom == "Species") {
            $Species = "selected";
        } else if ($kolom == "Metabolite") {
            $Metabolite = "selected";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>FMDB Homepage</title>
    <link href="<?=secure_url_output(base_url('_assets/css/bootstrap.min.css'));?>" rel="stylesheet">
    <link href="<?=secure_url_output(base_url('_assets/css/simple-sidebar.css'));?>" rel="stylesheet">
    <link rel="icon" href="<?=secure_url_output(base_url('_assets/favicon.svg'))?>">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
  <script src="<?=secure_url_output(base_url('_assets/js/jquery.js'))?>"></script>
  <script src="<?=secure_url_output(base_url('_assets/js/bootstrap.min.js'))?>"></script>
  <header>
    <div class="topcontainer">
      <div class="logo"></div>
      <div class="kiri">
        <a href="index.php">
          <h1 class="judul">FMDB</h1>
        </a>
      </div>
      <div class="navbar">
        <a href="<?=secure_url_output(base_url('home/metabolite.php'))?>">Browse</a>
        <a role="link" aria-disabled="true">Search</a>
        <a role="link" aria-disabled="true">Download</a>

        <div class="dropdownhome">
          <button class="dropbtn">About
            <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdownhome-content">
            <a href="<?=secure_url_output(base_url('home/about.php'))?>">About</a>
            <a href="<?=secure_url_output(base_url('home/contact.php'))?>">Contact Us</a>
          </div>
        </div>
        <a href="<?=secure_url_output(base_url('auth/index.html'))?>">Login</a>
      </div>
      <div class="kanan">
        <form class="form-inline" action="metabolite.php" method="post">
            <div class="form-group">
                <input type="text" name="pencarian" class="form-control" placeholder="Enter your search term!">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hiden="true"></span></button>
            </div>
        </form>
      </div>
    </div>

    <div class="containerhomepage">
      <a href="https://www.brin.go.id/" target="_blank">
        <div class="logobrinbrowsepage">
          <img src="images/brin.png" alt="BRIN" width="200" height="75">
        </div>
      </a>
      <div class="shortdescriptionbrowsepage">
        <p>Quantitative data of fish metabolies for biomarkers and detection of artificial compounds.</p>
      </div>
    </div>
  </header>

    <main>
      <div class="box">
        <h1><strong>Search Fish Metabolite Data</strong></h1>
        <br>

          <div style="margin-bottom: 20px; ">
            <form class= "form-inline" action="<?= secure_output($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="form-group">
                        <select class="form-control" name="kolom" required display: inline-block>
                              <option value="" >Select Search Term</option>
                              <option value="Species" <?= secure_output($Species) ?> >Species Name</option>
                              <option value="Metabolite" <?= secure_output($Metabolite) ?> >Metabolite Name</option>
                       </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="kata_kunci" value="<?= secure_output($kata_kunci);?>" class="form-control" display: inline-block required/>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hiden="true"></span></button>
                    </div>
                    <div class="form-group">
                        <input type="reset" name="reset" value="reset" class="btn btn-default">
                    </div>
          </form>
          </div>

        <br>

        <?php
        if ($showTable && !empty($kata_kunci) && !empty($kolom)) {

            $search_term = "%" . $kata_kunci . "%";
            $table = ($kolom == "Species") ? "species_metabolite" : "metabolite_species";
            $search_field = $kolom;
            
            // CRITICAL SQL INJECTION FIX: Use Prepared Statements
            $sql = "SELECT * FROM {$table} WHERE {$search_field} LIKE ?";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo "<div class='alert alert-danger'>Database error: Could not prepare statement.</div>";
                exit;
            }
            
            // Bind the search term as a string
            $stmt->bind_param("s", $search_term);
            $stmt->execute();
            $hasil = $stmt->get_result();
            
            // Fetch the column names for the header (only needed once)
            $fields = $hasil->fetch_fields();
            echo '<table class="table table-striped table-bordered table-hover"><thead><tr>';
            foreach ($fields as $field) {
                 // XSS Fix: Securely output column names (though typically safe, it's good practice)
                 echo "<th>" . secure_output($field->name) . "</th>";
            }
            echo '</tr></thead><tbody>';

            if ($hasil->num_rows > 0) {
                while ($data = $hasil->fetch_assoc()) {
                    
                    echo '<tr>';
                    if ($kolom == "Species") {
                        // Assuming the columns exist in species_metabolite view/table
                        ?>
                        <td><?=secure_output($data['Species'])?></td>
                        <td><?=secure_output($data['Habitat'])?></td>
                        <td><?=secure_output($data['Location'])?></td>
                        <td><img src="../images/<?=secure_output($data['Spectrum'])?>" alt="Spectrum"></td>
                        <td><?=secure_output($data['Metabolite'])?></td>
                        <td><?=secure_output($data['Quantity'])?></td>
                        <?php
                    } else { // Metabolite
                        // Assuming the columns exist in metabolite_species view/table
                        ?>
                        <td><?=secure_output($data['Metabolite'])?></td>
                        <td><?=secure_output($data['Detail'])?></td>
                        <td><img src="../images/<?=secure_output($data['Structure'])?>" alt="Structure"></td>
                        <td><?=secure_output($data['Quantity'])?></td>
                        <td><?=secure_output($data['Species'])?></td>
                        <?php
                    }
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="' . count($fields) . '" align="center">Data tidak ditemukan</td></tr>';
            }
            
            echo '</tbody></table>';
            $stmt->close();
            
        } else if ($showTable) {
             echo '<div class="alert alert-warning">Silakan masukkan kata kunci dan pilih kolom pencarian.</div>';
        }
        ?>

    </main>
    
    <footer>
    <div class="footcontainer">
      <div class="projectinfo">
        <h2 align="justify">This project is a collaboration between the Food Proces Technology Research Center and Data and Information Science Research Center.</h2>
        <h2 align="justify">National Research and Innovation Agency</h2>
      </div>
      <div class="address">
        <h2>BRIN</h2>
        <h2>Badan Riset dan Inovasi Nasional</h2>
        <h2 align="justify">Alamat: Gedung BJ. Habibie, Jl. M. H. Thamrin No. 8 Jakarta Pusat 10340. Whatsapp: +62811-1933-3639 ; Email: ppid@brin.go.id</h2>
      </div>
    </div>
  </footer>
</body>
</html>