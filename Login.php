<?php
session_start();
include "Router.php"; // koneksi DB

if (isset($_POST['login'])) {
    $nama  = $_POST['nama'];
    $sandi = $_POST['sandi'];

    $query = mysqli_query($koneksi,
        "SELECT * FROM pengguna 
         WHERE nama='$nama' AND sandi='$sandi'"
    );

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        $_SESSION['login']  = true;
        $_SESSION['nama']   = $data['nama'];
        $_SESSION['status'] = $data['status'];

        header("Location: Dashbord.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login</title>
<style>
body{
    margin:0;height:100vh;display:flex;justify-content:center;align-items:center;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#3b5f6f,#4fa3b1);
}
.login-box{
    background:#fff;padding:45px 60px;width:500px;border-radius:9px;
    box-shadow:0 4px 10px rgba(0,0,0,0.2);text-align:center;
}
.form-group{text-align:left;margin-bottom:12px;}
label{font-size:24px;font-weight:bold;}
input{width:100%;padding:10px;margin-top:5px;border:1px solid #ccc;border-radius:4px;}
.error{color:red;font-size:22px;margin-bottom:10px;}
button{
    width:100%;padding:20px;border:none;background:#2fa4a9;color:black;
    border-radius:5px;cursor:pointer;
}
</style>
</head>
<body>

<div class="login-box">
<h1>Login</h1>

<form method="post">
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="nama" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="sandi" required>
    </div>

    <?php if (!empty($error)) { ?>
        <div class="error"><?= $error ?></div>
    <?php } ?>

    <button type="submit" name="login"><H1>Masuk</H1></button>
</form>
</div>

</body>
</html>