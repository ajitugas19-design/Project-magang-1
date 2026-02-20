<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
include 'Router.php';

// Query untuk mengambil data BU (hanya value untuk datalist)
$query_bu = mysqli_query($koneksi, "SELECT * FROM bu ORDER BY bu ASC");
$bu_list = "";
while ($row = mysqli_fetch_assoc($query_bu)) {
    $bu_list .= "<option value='" . $row['bu'] . "'>";
}

// Query untuk mengambil data Material (hanya value untuk datalist)
$query_material = mysqli_query($koneksi, "SELECT * FROM material ORDER BY material ASC");
$material_list = "";
while ($row = mysqli_fetch_assoc($query_material)) {
    $material_list .= "<option value='" . $row['material'] . "'>";
}

// Query untuk mengambil kode terakhir dan generate kode baru secara otomatis
$query_last_kode = mysqli_query($koneksi, "SELECT kode FROM data_km ORDER BY id DESC LIMIT 1");
if (mysqli_num_rows($query_last_kode) > 0) {
    $row_kode = mysqli_fetch_assoc($query_last_kode);
    $last_kode = $row_kode['kode'];
    // Extract numeric part and increment
    $num = (int)substr($last_kode, 2);
    $new_num = $num + 1;
    $kode_baru = "K-" . str_pad($new_num, 6, "0", STR_PAD_LEFT);
} else {
    // Jika belum ada data, mulai dari K-000001
    $kode_baru = "K-000001";
}

// Pengeditan Data
$search_nopol = $_GET['search_nopol'] ?? '';
$search_date = $_GET['search_date'] ?? '';
$edit_id = $_GET['id'] ?? '';
$data = null;

if (!empty($edit_id)) {
    $q = mysqli_query($koneksi, "SELECT * FROM data_km WHERE id='$edit_id'");
    $data = mysqli_fetch_assoc($q);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Input KM</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f2f2f2; margin: 0; }
        .container { width:900px; margin:30px auto; background:#fff; padding:20px; border-radius:8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align:center; }
        .form-row { display:flex; gap:20px; margin-bottom:10px; }
        .form-group { flex:1; display:flex; align-items:center; }
        label { width:150px; font-weight:bold; }
        input, select { flex:1; padding:8px; border-radius:4px; border:1px solid #ccc; }
        input[readonly] { background-color: #e9ecef; }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center; 
            padding: 15px 30px;
            margin-bottom: 25px;
            background-color: #f8f9fa; 
            border-bottom: 1px solid #ddd;
        }
        .navbar a {
            display: inline-block;
            padding: 8px 18px;
            background-color: #007bff;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<!--Isi agar code otomatis-->
    <?php if (isset($_SESSION['success'])): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #c3e6cb;">
            <?= $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #f5c6cb;">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

<div class="container">
    <div class="navbar">
        <div class="left">Login sebagai: <b><?= $_SESSION['nama']; ?></b></div>
        <h2 style="margin: 0;">FORM INPUT KM</h2>
        <div class="right"><a href="Cekdata.php">Cek Data</a></div>
    </div>

    <form method="POST" action="simpan.php">
        <div class="form-row">
            <div class="form-group">
                <label>Kode</label>
                <input type="text" name="kode" value="<?= $kode_baru ?>" readonly>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nopol</label>
                <input type="text" name="nopol">
            </div>
            <div class="form-group">
                <label>Kendaraan</label>
                <input type="text" name="kendaraan">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nama Sopir</label>
                <input type="text" name="sopir">
            </div>
            <div class="form-group">
    <label>Status</label>
    <input type="text" id="statusInput" name="status" value="CLOSE" class="form-control">
</div>
        </div>

        <hr>

        <div class="form-row">
            <div class="form-group">
                <label>Tanggal Keluar</label>
                <input type="date" name="tgl_out" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date" name="tgl_in" value="<?= date('Y-m-d') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Jam Keluar</label>
                <input type="time" name="jam_out" value="<?= date('H:i') ?>">
            </div>
            <div class="form-group">
                <label>Jam Masuk</label>
                <input type="time" name="jam_in" value="<?= date('H:i') ?>">
            </div>
        </div>

        <hr>

        <div class="form-row">
            <div class="form-group">
                <label>BU</label>
                <input type="text" name="bu" list="data_bu" placeholder="Pilih atau ketik BU...">
                <datalist id="data_bu">
                    <?= $bu_list ?>
                </datalist>
            </div>
            <div class="form-group">
                <label>BU 2</label>
                <input type="text" name="bu2" list="data_bu" placeholder="Pilih atau ketik BU...">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Material</label>
                <input type="text" name="material" list="data_material" placeholder="Pilih atau ketik Material...">
                <datalist id="data_material">
                    <?= $material_list ?>
                </datalist>
            </div>
            <div class="form-group">
                <label>Material 2</label>
                <input type="text" name="material2" list="data_material" placeholder="Pilih atau ketik Material...">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Keterangan</label>
                <input type="text" name="ket">
            </div>
            <div class="form-group">
                <label>Keterangan 2</label>
                <input type="text" name="ket2">
            </div>
        </div>

        <hr>

        <div class="form-row">
            <div class="form-group">
                <label>KM Datang</label>
                <input type="number" name="km_datang" id="km_datang">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>KM Keluar</label>
                <input type="number" name="km_keluar" id="km_keluar">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>KM Total</label>
                <input type="number" name="km_total" id="km_total" readonly style="font-weight: bold; color: blue;">
            </div>
        </div>

        <br>
        <div style="text-align: right;">
            <button type="submit" style="background-color: green; color: white; padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                Simpan Data
            </button>
        </div>
    </form>
</div>

<script>
const datang = document.getElementById("km_datang");
const keluar = document.getElementById("km_keluar");
const total = document.getElementById("km_total");

function hitungKM(){
    let d = parseInt(datang.value) || 0;
    let k = parseInt(keluar.value) || 0;
    total.value = k - d;
}

datang.addEventListener("input", hitungKM);
keluar.addEventListener("input", hitungKM);

// Handle Enter key to move to next field
document.querySelectorAll('input, select').forEach(function(field, index, fields) {
    field.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const nextField = fields[index + 1];
            if (nextField) {
                nextField.focus();
            }
        }
    });
});
</script>

</body>
</html>