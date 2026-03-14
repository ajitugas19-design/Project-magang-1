<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'Router.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $query = "DELETE FROM data_km WHERE id = '$id'";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['success'] = "Data berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Error hapus: " . mysqli_error($koneksi);
    }
}

$search_nopol = $_POST['search_nopol'] ?? $_GET['search_nopol'] ?? '';
$search_date = $_POST['search_date'] ?? $_GET['search_date'] ?? '';

$redirect = "Cekdata.php?search_nopol=" . urlencode($search_nopol) . "&search_date=" . urlencode($search_date);
header("Location: $redirect");
exit;
?>

