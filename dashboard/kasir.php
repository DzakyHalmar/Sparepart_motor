<?php

session_start();

if(!isset($_SESSION['id_user'])){
    header("Location: ../auth/login.php");
    exit;
}

if($_SESSION['role'] != 'kasir'){
    header("Location: admin.php");
    exit;
}

?>

<h1>Dashboard Kasir</h1>

<h3>
Selamat Datang,
<?php echo $_SESSION['nama']; ?>
</h3>

<p>
Role :
<?php echo $_SESSION['role']; ?>
</p>

<a href="../auth/logout.php">
Logout
</a>