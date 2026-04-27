<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$koneksi = mysqli_connect("localhost","root","","km1");

if (!$koneksi) {
    die("Koneksi database gagal");
}
?>