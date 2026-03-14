<?php
$koneksi = mysqli_connect("localhost","root","","km1");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
