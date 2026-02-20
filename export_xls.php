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

// Set headers for Excel download
$filename = "data_km_" . date('Y-m-d') . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table border="1">
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
<tr>
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
<td><?= htmlspecialchars($row['km_total']) ?></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="18">Data tidak ditemukan</td>
</tr>
<?php endif; ?>
</tbody>
</table>
