<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
include 'Router.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data
    $kode      = $_POST['kode'] ?? '';
    $nopol     = $_POST['nopol'] ?? '';
    $kendaraan = $_POST['kendaraan'] ?? '';
    $sopir     = $_POST['sopir'] ?? '';
    $status    = $_POST['status'] ?? '';
    $tgl_out   = $_POST['tgl_out'] ?? '';
    $tgl_in    = $_POST['tgl_in'] ?? '';
    $jam_out   = $_POST['jam_out'] ?? '';
    $jam_in    = $_POST['jam_in'] ?? '';
    $bu        = $_POST['bu'] ?? '';
    $bu2       = $_POST['bu2'] ?? '';
    $material  = $_POST['material'] ?? '';
    $material2 = $_POST['material2'] ?? '';
    $ket       = $_POST['ket'] ?? '';
    $ket2      = $_POST['ket2'] ?? '';
    $km_datang = $_POST['km_datang'] ?? '';
    $km_keluar = $_POST['km_keluar'] ?? '';
    $km_total  = $_POST['km_total'] ?? '';

    // Check if table exists, if not create it
    $table_check = mysqli_query($koneksi, "SHOW TABLES LIKE 'data_km'");
    if (mysqli_num_rows($table_check) == 0) {
        $create_table = "CREATE TABLE data_km (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            kode VARCHAR(50) NOT NULL,
            nopol VARCHAR(20) DEFAULT NULL,
            kendaraan VARCHAR(100) DEFAULT NULL,
            sopir VARCHAR(100) DEFAULT NULL,
            status VARCHAR(50) DEFAULT NULL,
            tgl_out DATE DEFAULT NULL,
            tgl_in DATE DEFAULT NULL,
            jam_out TIME DEFAULT NULL,
            jam_in TIME DEFAULT NULL,
            bu VARCHAR(100) DEFAULT NULL,
            bu2 VARCHAR(100) DEFAULT NULL,
            material VARCHAR(100) DEFAULT NULL,
            material2 VARCHAR(100) DEFAULT NULL,
            ket VARCHAR(255) DEFAULT NULL,
            ket2 VARCHAR(255) DEFAULT NULL,
            km_datang INT(11) DEFAULT NULL,
            km_keluar INT(11) DEFAULT NULL,
            km_total INT(11) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        mysqli_query($koneksi, $create_table);
    }

    // Check and create bu table if not exists
    $table_bu = mysqli_query($koneksi, "SHOW TABLES LIKE 'bu'");
    if (mysqli_num_rows($table_bu) == 0) {
        mysqli_query($koneksi, "CREATE TABLE bu (id INT(11) AUTO_INCREMENT PRIMARY KEY, bu VARCHAR(100) NOT NULL)");
        // Insert sample data
        mysqli_query($koneksi, "INSERT INTO bu (bu) VALUES ('PT ABC'), ('PT XYZ'), ('PT DEF'), ('PT GHI')");
    }

    // Check and create material table if not exists
    $table_material = mysqli_query($koneksi, "SHOW TABLES LIKE 'material'");
    if (mysqli_num_rows($table_material) == 0) {
        mysqli_query($koneksi, "CREATE TABLE material (id INT(11) AUTO_INCREMENT PRIMARY KEY, material VARCHAR(100) NOT NULL)");
        // Insert sample data
        mysqli_query($koneksi, "INSERT INTO material (material) VALUES ('Material A'), ('Material B'), ('Material C'), ('Material D')");
    }

    // Check if this is an update (edit) or new insert
    $edit_id = $_POST['edit_id'] ?? '';
    $search_nopol = $_POST['search_nopol'] ?? '';
    $search_date = $_POST['search_date'] ?? '';

    if (!empty($edit_id)) {
        // UPDATE existing record
        $query = "UPDATE data_km SET 
            kode = '$kode',
            nopol = '$nopol',
            kendaraan = '$kendaraan',
            sopir = '$sopir',
            status = '$status',
            tgl_out = '$tgl_out',
            tgl_in = '$tgl_in',
            jam_out = '$jam_out',
            jam_in = '$jam_in',
            bu = '$bu',
            bu2 = '$bu2',
            material = '$material',
            material2 = '$material2',
            ket = '$ket',
            ket2 = '$ket2',
            km_datang = '$km_datang',
            km_keluar = '$km_keluar',
            km_total = '$km_total'
            WHERE id = '$edit_id'";

        $result = mysqli_query($koneksi, $query);

        if ($result) {
            // Success - redirect back to Cekdata with success message and search parameters
            $_SESSION['success'] = "Data berhasil diupdate!";
            $redirect_url = "Cekdata.php?search_nopol=" . urlencode($search_nopol) . "&search_date=" . urlencode($search_date);
            header("Location: " . $redirect_url);
            exit;
        } else {
            // Error
            $_SESSION['error'] = "Gagal mengupdate data: " . mysqli_error($koneksi);
            $redirect_url = "Cekdata.php?search_nopol=" . urlencode($search_nopol) . "&search_date=" . urlencode($search_date);
            header("Location: " . $redirect_url);
            exit;
        }
    } else {
        // INSERT new record
        $query = "INSERT INTO data_km (
            kode, nopol, kendaraan, sopir, status, 
            tgl_out, tgl_in, jam_out, jam_in, 
            bu, bu2, material, material2, ket, ket2, 
            km_datang, km_keluar, km_total
        ) VALUES (
            '$kode', '$nopol', '$kendaraan', '$sopir', '$status',
            '$tgl_out', '$tgl_in', '$jam_out', '$jam_in',
            '$bu', '$bu2', '$material', '$material2', '$ket', '$ket2',
            '$km_datang', '$km_keluar', '$km_total'
        )";

        $result = mysqli_query($koneksi, $query);

        if ($result) {
            // Success - redirect back to dashboard with success message
            $_SESSION['success'] = "Data berhasil disimpan!";
            header("Location: Dashbord.php");
            exit;
        } else {
            // Error
            $_SESSION['error'] = "Gagal menyimpan data: " . mysqli_error($koneksi);
            header("Location: Dashbord.php");
            exit;
        }
    }
} else {
    // If accessed directly without POST, redirect to dashboard
    header("Location: Dashbord.php");
    exit;
}
?>
