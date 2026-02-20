<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'Router.php';

$search_nopol = $_GET['search_nopol'] ?? '';
$search_date  = $_GET['search_date'] ?? '';

$query = "SELECT * FROM data_km WHERE 1=1";

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
<title>Data KM</title>
<style>
*{ box-sizing:border-box; }

body { 
    font-family:Segoe UI, Arial, sans-serif; 
    background:#eef2f5; 
    padding:15px; 
    margin:0;
}

.container { 
    max-width:1500px; 
    margin:auto; 
    background:white; 
    border-radius:8px;
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
    min-width:1500px; 
}

th,td { 
    border:1px solid #ddd; 
    padding:7px 8px; 
    font-size:13px; 
    white-space:nowrap;
}

th { 
    background:#f1f1f1; 
    text-align:center; 
}

tbody tr { cursor:pointer; }

tbody tr:hover { 
    background:#e3f2fd; 
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
<th>KODE</th>
<th>NOPOL</th>
<th>SOPIR</th>
<th>KENDARAAN</th>
<th>STATUS</th>
<th>TGL KELUAR</th>
<th>JAM KELUAR</th>
<th>BU</th>
<th>MATERIAL</th>
<th>KET 1</th>
<th>TGL MASUK</th>
<th>JAM MASUK</th>
<th>BU 2</th>
<th>MATERIAL 2</th>
<th>KET 2</th>
<th>KM KELUAR</th>
<th>KM MASUK</th>
<th>TOTAL KM</th>
</tr>
</thead>
<tbody>
<?php if(mysqli_num_rows($result)>0): ?>
<?php while($row=mysqli_fetch_assoc($result)): ?>
<tr onclick="window.location='edit.php?id=<?= $row['id'] ?>&search_nopol=<?= urlencode($search_nopol) ?>&search_date=<?= urlencode($search_date) ?>'">
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
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="18" style="text-align:center;color:#888;padding:20px;">
Data tidak ditemukan
</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>

<p style="text-align:center;font-size:11px;color:#888;">
* Klik baris untuk mengedit data
</p>
<a href="export_xls.php?search_date=<?= urlencode($search_date) 
?>&search_nopol=<?= urlencode($search_nopol) 
?>" class="btn btn-green btn-right">&larr; EXPORT</a>
</body>
</html>
