<?php
$koneksi = mysqli_connect("localhost","root","","km");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
