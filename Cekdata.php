<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'Router.php';

$search_nopol = $_GET['search_nopol'] ?? '';
$search_date  = $_GET['search_date'] ?? '';

$query = "SELECT dk.*, k.sopir, k.kendaraan 
          FROM data_km dk 
          LEFT JOIN kendaraan k ON dk.nopol = k.nopol 
          WHERE 1=1";

if (!empty($search_nopol)) {
    $query .= " AND nopol LIKE '%" . mysqli_real_escape_string($koneksi, $search_nopol) . "%'";
}

if (!empty($search_date)) {
    $query .= " AND (tgl_out = '" . mysqli_real_escape_string($koneksi, $search_date) . "' 
               OR tgl_in = '" . mysqli_real_escape_string($koneksi, $search_date) . "')";
}

$query .= " ORDER BY created_at DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><h1>Data KM</h1></title>
<style>
*{ box-sizing:border-box; }

body { 
    font-family:Segoe UI, Arial, sans-serif; 
    background:#eef2f5; 
    padding:15px; 
    margin:0;
}

.container { 
    max-width: 100%; 
    margin: 0 auto; 
    background: white; 
    border-radius: 8px;
    box-shadow:0 2px 6px rgba(0,0,0,.1);
    overflow:hidden;
}

/* HEADER */
.header { 
    display:flex; 
    justify-content:space-between; 
    align-items:center;
    padding:15px 20px; 
    border-bottom:1px solid #27c8f0; 
    background:white;
    color:black;
}
.header h2{ margin:0; }

/* SEARCH */
.search-section { 
    padding:15px 20px; 
    background:#f9f9f9; 
    border-bottom:1px solid #ddd; 
}

.form-group { 
    display:flex; 
    gap:10px; 
    flex-wrap:wrap; 
    align-items:end;
}

.input-box { 
    flex:1; 
    min-width:200px; 
}

.input-box label { 
    font-size:12px; 
    font-weight:bold; 
    margin-bottom:4px;
}

.input-box input { 
    width:100%; 
    padding:8px; 
    border:1px solid #ccc; 
    border-radius:4px;
}

/* BUTTON */
.btn { 
    padding:9px 16px; 
    border:none; 
    border-radius:4px; 
    font-weight:bold; 
    cursor:pointer; 
    text-decoration:none;
}
.btn-right{
    float: right;
}

.btn-gray { background:#6c757d; color:white; }
.btn-blue { background:#007bff; color:white; }
.btn-green { background:#28a745; color:white; }

/* TABLE */
.table-container { overflow-x:auto; }

table { 
    width:100%; 
    border-collapse:collapse; 
    table-layout:fixed; 
}

th,td { 
    border:1px solid #ddd; 
    padding:6px 4px; 
    font-size:12px; 
    white-space:normal;
    word-wrap:break-word;
    overflow-wrap: break-word;
    hyphens: auto;
}

th { 
    background:#f1f1f1; 
    text-align:center; 
}

tbody tr:hover { 
    background:#e3f2fd; 
}
td form button:hover {
    background:#c82333 !important;
}

td:last-child{
    font-weight:bold;
    color:#007bff;
}

/* RESPONSIVE */
@media(max-width:600px){
    .form-group{ flex-direction:column; }
}

</style>
</head>
<body>

<div class="container">
<div class="header">
<h2>DAFTAR DATA KM</h2>
<a href="Dashbord.php" class="btn btn-blue">&larr; KEMBALI</a>
</div>

<div class="search-section">
<form method="GET">
<div class="form-group">
    <div class="input-box">
        <label>TANGGAL</label>
        <input type="date" name="search_date" value="<?= $search_date ?>">
    </div>
    <div class="input-box">
        <label>NOPOL</label>
        <input type="text" name="search_nopol" value="<?= $search_nopol ?>">
    </div>
    <button type="submit" class="btn btn-blue">CARI</button>
    <a href="Cekdata.php" class="btn btn-gray">RESET</a>
</div>
</form>
</div>

<div class="table-container">
<table>
<thead>
<tr>
<th style="width:60px;">KODE</th>
<th style="width:90px;">NOPOL</th>
<th style="width:120px;">SOPIR</th>
<th style="width:130px;">KENDARAAN</th>
<th style="width:70px;">STATUS</th>
<th style="width:90px;">TGL OUT</th>
<th style="width:70px;">JAM OUT</th>
<th style="width:60px;">BU 1</th>
<th style="width:110px;">MAT. 1</th>
<th style="width:80px;">KET 1</th>
<th style="width:90px;">TGL IN</th>
<th style="width:70px;">JAM IN</th>
<th style="width:60px;">BU 2</th>
<th style="width:110px;">MAT. 2</th>
<th style="width:80px;">KET 2</th>
<th style="width:80px;">KM OUT</th>
<th style="width:80px;">KM IN</th>
<th style="width:90px;">TOTAL</th>
<th style="width:100px;">AKSI</th>
</tr>
</thead>
<tbody>
<?php if(mysqli_num_rows($result)>0): ?>
<?php while($row=mysqli_fetch_assoc($result)): ?>
<tr style="cursor:default;" ondblclick="window.location='edit.php?id=<?= $row['id'] ?>&search_nopol=<?= urlencode($search_nopol) ?>&search_date=<?= urlencode($search_date) ?>'">
<td><?= htmlspecialchars($row['kode']) ?></td>
<td><?= htmlspecialchars($row['nopol']) ?></td>
<td><?= htmlspecialchars($row['sopir']) ?></td>
<td><?= htmlspecialchars($row['kendaraan']) ?></td>
<td><?= htmlspecialchars($row['status']) ?></td>
<td><?= htmlspecialchars($row['tgl_out']) ?></td>
<td><?= htmlspecialchars($row['jam_out']) ?></td>
<td><?= htmlspecialchars($row['bu']) ?></td>
<td><?= htmlspecialchars($row['material']) ?></td>
<td><?= htmlspecialchars($row['ket']) ?></td>
<td><?= htmlspecialchars($row['tgl_in']) ?></td>
<td><?= htmlspecialchars($row['jam_in']) ?></td>
<td><?= htmlspecialchars($row['bu2']) ?></td>
<td><?= htmlspecialchars($row['material2']) ?></td>
<td><?= htmlspecialchars($row['ket2']) ?></td>
<td><?= htmlspecialchars($row['km_keluar']) ?></td>
<td><?= htmlspecialchars($row['km_datang']) ?></td>
<td style="font-weight:bold;"><?= htmlspecialchars($row['km_total']) ?></td>
<td>
    <form method="post" action="hapus.php" style="display:inline" onsubmit="return confirm('Hapus data kode <?= $row['kode'] ?> ?')">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <input type="hidden" name="search_nopol" value="<?= htmlspecialchars($search_nopol) ?>">
        <input type="hidden" name="search_date" value="<?= htmlspecialchars($search_date) ?>">
        <button type="submit" class="btn" style="background:#dc3545;color:white;padding:5px 10px;font-size:12px;border:none;border-radius:3px;cursor:pointer">HAPUS</button>
    </form>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="19" style="text-align:center;color:#888;padding:20px;">
Data tidak ditemukan
</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>

<p style="text-align:center;font-size:11px;color:#888;">
    Click 2x pada table untuk mengedit
* Klik baris untuk mengedit data
</p>
<a href="export_xls.php?search_date=<?= urlencode($search_date) 
?>&search_nopol=<?= urlencode($search_nopol) 
?>" class="btn btn-green btn-right">&larr; EXPORT</a>
</body>
</html>
