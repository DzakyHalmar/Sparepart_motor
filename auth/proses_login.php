<?php

session_start();
include '../config/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM users
     WHERE username='$username'
     AND password='$password'"
);

if(mysqli_num_rows($query) > 0){

    $data = mysqli_fetch_assoc($query);

    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['role'] = $data['role'];

    if($data['role'] == 'admin'){

        header("Location: ../dashboard/admin.php");

    }elseif($data['role'] == 'kasir'){

        header("Location: ../dashboard/kasir.php");

    }

}else{

    echo "
    <script>
        alert('Username atau Password Salah!');
        window.location='login.php';
    </script>
    ";

}
?>