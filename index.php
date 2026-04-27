<?php
session_start();

// Jika sudah login → ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit;
}