<?php
session_start();
require_once "config.php";

$page = $_GET['page'] ?? 'dashboard';

// ================= PROTEKSI =================
if (!isset($_SESSION['login'])) {
    $page = 'login';
}

// Kalau sudah login, tidak boleh balik ke login
if (isset($_SESSION['login']) && $page == 'login') {
    $page = 'dashboard';
}

// ================= ROUTING =================
switch ($page) {

    case 'dashboard':
        require 'Dashbord.php';
        break;

    case 'cekdata':
        require 'Cekdata.php';
        break;

    case 'login':
        require 'Login.php';
        break;

    case 'logout':
        session_destroy();
        header("Location: Router.php?page=login");
        exit;

    default:
        echo "<h3>Halaman tidak ditemukan</h3>";
}
?>