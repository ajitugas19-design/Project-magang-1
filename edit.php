<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
include 'Router.php';

// Get the ID from URL
$edit_id = $_GET['id'] ?? '';
$search_nopol = $_GET['search_nopol'] ?? '';
$search_date = $_GET['search_date'] ?? '';

// Fetch existing data
$data = null;
if (!empty($edit_id)) {
    $q = mysqli_query($koneksi, "SELECT * FROM data_km WHERE id='$edit_id'");
    $data = mysqli_fetch_assoc($q);
}

// If no data found, redirect back
if (!$data) {
    header("Location: Cekdata.php");
    exit;
}

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

// 🚀 NEW: Kendaraan datalist untuk autocomplete
$query_kendaraan = mysqli_query($koneksi, "SELECT nopol, sopir, kendaraan FROM kendaraan ORDER BY nopol ASC");
$kendaraan_list = "";
$kendaraan_data = []; 
while ($row = mysqli_fetch_assoc($query_kendaraan)) {
    $kendaraan_list .= "<option value='" . htmlspecialchars($row['nopol']) . "'>";
    $kendaraan_data[] = [
        'nopol' => $row['nopol'],
        'sopir' => $row['sopir'],
        'kendaraan' => $row['kendaraan']
    ];
}
$kendaraan_json = json_encode($kendaraan_data);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data KM</title>
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
        .btn-back {
            background-color: #077fe9 !important;
        }
    </style>
</head>
<body>

<!-- Success/Error Messages -->
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
        <h2 style="margin: 0;">FORM EDIT KM</h2>
        <div class="right">
            <a href="Cekdata.php?search_nopol=<?= urlencode($search_nopol)
             ?>&search_date=<?= urlencode($search_date) 
             ?>" class="btn-back">⟵ KEMBALI</a>
        </div>
    </div>

    <form method="POST" action="simpan.php">
        <!-- Hidden field for edit ID -->
        <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
        <!-- Preserve search parameters -->
        <input type="hidden" name="search_nopol" value="<?= htmlspecialchars($search_nopol) ?>">
        <input type="hidden" name="search_date" value="<?= htmlspecialchars($search_date) ?>">
        
        <div class="form-row">
            <div class="form-group"style="background-color: grey; padding:5px;">
                <label>Kode</label>
                <input type="text" name="kode" value="<?= htmlspecialchars($data['kode']) ?>" readonly>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group"style="background-color: lime; padding:5px;">
                <label>Nopol <small>(Auto)</small></label>
                <input type="text" name="nopol" list="data_kendaraan" id="nopol_input" value="<?= htmlspecialchars($data['nopol']) ?>" placeholder="Ketik nopol...">
                <datalist id="data_kendaraan">
                    <?= $kendaraan_list ?>
                </datalist>
            </div>
            <div class="form-group"style="background-color: red; padding:5px;">
                <label>Kendaraan</label>
                <input type="text" name="kendaraan" value="<?= htmlspecialchars($data['kendaraan']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group"style="background-color: lime; padding:5px;">
                <label>Nama Sopir</label>
                <input type="text" name="sopir" value="<?= htmlspecialchars($data['sopir']) ?>">
            </div>
            <div class="form-group"style="background-color: red; padding:5px;">
                <label>Status</label>
                <input type="text" id="statusInput" name="status" value="<?= htmlspecialchars($data['status']) ?>" class="form-control">
            </div>
        </div>

        <hr>

        <div class="form-row">
            <div class="form-group"style="background-color: lime; padding:5px;">
                <label>Tanggal Keluar</label>
                <input type="date" name="tgl_out" value="<?= htmlspecialchars($data['tgl_out']) ?>">
            </div>
            <div class="form-group"style="background-color: red; padding:5px;">
                <label>Tanggal Masuk</label>
                <input type="date" name="tgl_in" value="<?= htmlspecialchars($data['tgl_in']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group"style="background-color: lime; padding:5px;">
                <label>Jam Keluar</label>
                <input type="time" name="jam_out" value="<?= htmlspecialchars($data['jam_out']) ?>">
            </div>
            <div class="form-group"style="background-color: red; padding:5px;">
                <label>Jam Masuk</label>
                <input type="time" name="jam_in" value="<?= htmlspecialchars($data['jam_in']) ?>">
            </div>
        </div>

        <hr>

        <div class="form-row">
            <div class="form-group"style="background-color: lime; padding:5px;">
                <label>BU 1</label>
                <input type="text" name="bu" list="data_bu" value="<?= htmlspecialchars($data['bu']) ?>" placeholder="Pilih atau ketik BU...">
                <datalist id="data_bu">
                    <?= $bu_list ?>
                </datalist>
            </div>
            <div class="form-group"style="background-color: red; padding:5px;">
                <label>BU 2</label>
                <input type="text" name="bu2" list="data_bu" value="<?= htmlspecialchars($data['bu2']) ?>" placeholder="Pilih atau ketik BU...">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group"style="background-color: lime; padding:5px;">
                <label>Material 1</label>
                <input type="text" name="material" list="data_material" value="<?= htmlspecialchars($data['material']) ?>" placeholder="Pilih atau ketik Material...">
                <datalist id="data_material">
                    <?= $material_list ?>
                </datalist>
            </div>
            <div class="form-group"style="background-color: red; padding:5px;">
                <label>Material 2</label>
                <input type="text" name="material2" list="data_material" value="<?= htmlspecialchars($data['material2']) ?>" placeholder="Pilih atau ketik Material...">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group"style="background-color: lime; padding:5px;">
                <label>Keterangan 1</label>
                <input type="text" name="ket" value="<?= htmlspecialchars($data['ket']) ?>">
            </div>
            <div class="form-group"style="background-color: red; padding:5px;">
                <label>Keterangan 2</label>
                <input type="text" name="ket2" value="<?= htmlspecialchars($data['ket2']) ?>">
            </div>
        </div>

        <hr>

        <div class="form-row">
            <div class="form-group"style="background-color: lime; padding:5px;">
                <label>KM Keluar</label>
                <input type="number" name="km_keluar" id="km_keluar" value="<?= htmlspecialchars($data['km_keluar']) ?>">
            </div>

            <div class="form-group"style="background-color: red; padding:5px;">
                <label>KM masuk</label>
                <input type="number" name="km_datang" id="km_datang" value="<?= htmlspecialchars($data['km_datang']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group"style="background-color: grey; padding:5px;">
                <label>KM Total</label>
                <input type="number" name="km_total" id="km_total" value="<?= htmlspecialchars($data['km_total']) ?>" readonly style="font-weight: bold; color: blue;">
            </div>
        </div>

        <br>
        <div style="text-align: right;">
            <button type="submit" style="background-color: green; color: white; padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                Update Data
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
    total.value = d - k;
}

datang.addEventListener("input", hitungKM);
keluar.addEventListener("input", hitungKM);

// 🚀 KENDAARAAN AUTOCOMPLETE 
const kendaraanData = <?= $kendaraan_json ?>;
const nopolInput = document.getElementById('nopol_input');
const sopirInput = document.querySelector('input[name="sopir"]');
const kendaraanInput = document.querySelector('input[name="kendaraan"]');

nopolInput.addEventListener('input', function() {
    const nopol = this.value.toUpperCase().trim();
    const match = kendaraanData.find(item => 
        item.nopol.toUpperCase() === nopol || 
        item.nopol.toUpperCase().startsWith(nopol)
    );
    
    if (match) {
        sopirInput.value = match.sopir;
        kendaraanInput.value = match.kendaraan;
        this.style.backgroundColor = '#d4edda';
    } else {
        sopirInput.value = '<?= htmlspecialchars($data['sopir'] ?? '') ?>';
        kendaraanInput.value = '<?= htmlspecialchars($data['kendaraan'] ?? '') ?>';
        this.style.backgroundColor = '';
    }
});

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
