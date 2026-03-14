<?php
include 'Router.php';

echo "<h2>🔄 MIGRASI KENDARAAN (Final Consolidated)</h2>";

// 1. Drop if exists (safe reset)
$drop_sql = "DROP TABLE IF EXISTS kendaraan";
if (mysqli_query($koneksi, $drop_sql)) {
    echo "✅ Tabel lama dihapus (safe)<br>";
} 

// 2. Create final structure (match km1.sql & app expectation)
$create_sql = "CREATE TABLE kendaraan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nopol VARCHAR(20) UNIQUE NOT NULL,
    sopir VARCHAR(100) NOT NULL,
    kendaraan VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if (mysqli_query($koneksi, $create_sql)) {
    echo "✅ Tabel 'kendaraan' dibuat dengan struktur final (sopir ✅)<br>";
} else {
    echo "❌ Error create: " . mysqli_error($koneksi);
    exit;
}

// 3. Migrate DISTINCT data dari data_km
$migrate_sql = "INSERT IGNORE INTO kendaraan (nopol, sopir, kendaraan)
                SELECT DISTINCT nopol, sopir, kendaraan 
                FROM data_km 
                WHERE nopol IS NOT NULL AND nopol != ''
                AND sopir IS NOT NULL AND kendaraan IS NOT NULL";
                
if (mysqli_query($koneksi, $migrate_sql)) {
    $count = mysqli_affected_rows($koneksi);
    echo "✅ $count record kendaraan dimigrasi dari data_km<br>";
} else {
    echo "ℹ️ Migration: " . mysqli_error($koneksi) . "<br>";
}

// 4. Verify structure
echo "<h3>✅ Struktur Tabel Final:</h3>";
$res = mysqli_query($koneksi, "DESCRIBE kendaraan");
echo "<table border='1' style='border-collapse:collapse'>";
echo "<tr style='background:#e9ecef'><th>Field</th><th>Type</th><th>Status</th></tr>";
while($row = mysqli_fetch_assoc($res)) {
    $status = (in_array($row['Field'], ['sopir', 'kendaraan', 'nopol'])) ? '✅ OK' : '⚠️';
    echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>$status</td></tr>";
}
echo "</table><br>";

// 5. Data preview
echo "<h3>📊 Preview Data (10 teratas):</h3>";
$data_res = mysqli_query($koneksi, "SELECT * FROM kendaraan ORDER BY nopol LIMIT 10");
$count_res = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kendaraan");
$total = mysqli_fetch_assoc($count_res)['total'];

echo "<p><strong>Total: $total record</strong></p>";
if (mysqli_num_rows($data_res) > 0) {
    echo "<table border='1' style='border-collapse:collapse'>";
    echo "<tr style='background:#e9ecef'><th>ID</th><th>NOPOL</th><th>SOPIR</th><th>KENDARAAN</th></tr>";
    while($row = mysqli_fetch_assoc($data_res)) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td><strong>{$row['nopol']}</strong></td>";
        echo "<td>{$row['sopir']}</td>";
        echo "<td>{$row['kendaraan']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:orange'>📭 Kosong - siap input data pertama!</p>";
}

echo "<hr>";
echo "<div style='background:#d4edda;padding:20px;border-radius:8px;margin:20px 0'>";
echo "<h3>🎉 MIGRASI SELESAI! Sistem siap 100%:</h3>";
echo "<ol>";
echo "<li><a href='Dashbord.php' style='font-size:16px'>→ Test Dashboard Autocomplete (ketik NOPOL)</a></li>";
echo "<li>NOPOL match → Sopir + Kendaraan otomatis muncul ✅</li>";
echo "<li>Simpan → <a href='Cekdata.php'>Cekdata.php</a></li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<a href='Dashbord.php' style='background:#28a745;color:white;padding:15px 30px;font-size:18px;text-decoration:none;border-radius:8px'>🚀 LANJUT TEST DASHBOARD</a>";
echo " | <a href='migrate_kendaraan.php'>Refresh Migration</a>";
?>
