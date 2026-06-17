<?php

include '../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_query(
    $koneksi,
    "SELECT * FROM sparepart
    WHERE id_sparepart='$id'"
);

$row = mysqli_fetch_assoc($data);

if(isset($_POST['update'])){

    $kode = $_POST['kode_sparepart'];
    $nama = $_POST['nama_sparepart'];
    $beli = $_POST['harga_beli'];
    $jual = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    mysqli_query(
        $koneksi,
        "UPDATE sparepart SET

        kode_sparepart='$kode',
        nama_sparepart='$nama',
        harga_beli='$beli',
        harga_jual='$jual',
        stok='$stok'

        WHERE id_sparepart='$id'"
    );

    header("Location: sparepart.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Sparepart</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<body>

    <div class="dashboard">

        <div class="navbar-admin">

            <h4>MotoParts Admin</h4>

            <a href="sparepart.php" class="logout-btn">
                Kembali
            </a>

        </div>

        <div class="container py-5">

            <h1 class="dashboard-title text-center">
                Edit Sparepart
            </h1>

            <div class="d-flex justify-content-center">

                <div class="login-card">

                    <form method="POST">

                        <div class="mb-3">

                            <label>Kode Sparepart</label>

                            <input
                            type="text"
                            name="kode_sparepart"
                            class="form-control"
                            value="<?= $row['kode_sparepart']; ?>"
                            required>

                        </div>

                        <div class="mb-3">

                            <label>Nama Sparepart</label>

                            <input
                            type="text"
                            name="nama_sparepart"
                            class="form-control"
                            value="<?= $row['nama_sparepart']; ?>"
                            required>

                        </div>

                        <div class="mb-3">

                            <label>Harga Beli</label>

                            <input
                            type="number"
                            name="harga_beli"
                            class="form-control"
                            value="<?= $row['harga_beli']; ?>"
                            required>

                        </div>

                        <div class="mb-3">

                            <label>Harga Jual</label>

                            <input
                            type="number"
                            name="harga_jual"
                            class="form-control"
                            value="<?= $row['harga_jual']; ?>"
                            required>

                        </div>

                        <div class="mb-3">

                            <label>Stok</label>

                            <input
                            type="number"
                            name="stok"
                            class="form-control"
                            value="<?= $row['stok']; ?>"
                            required>

                        </div>

                        <button
                        type="submit"
                        name="update"
                        class="btn btn-login">

                            Update Data

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</body>
</html>