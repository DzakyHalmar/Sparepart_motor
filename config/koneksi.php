<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_sparepart_motor";


$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal : " . mysqli_connect_error());
}
?>