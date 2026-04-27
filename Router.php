<?php
// GANTI Router.php menjadi ini

require_once "config.php";

$page = $_GET['page'] ?? 'login';

if (isset($_SESSION['login']) && $page == 'login') {
    $page = 'dashboard';
}

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
        echo "Halaman tidak ditemukan";
}
?>