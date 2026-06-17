<?php

include '../config/koneksi.php';

if(isset($_POST['simpan'])){

    $nama    = $_POST['nama_supplier'];
    $alamat  = $_POST['alamat'];
    $telepon = $_POST['telepon'];

    mysqli_query(
        $koneksi,
        "INSERT INTO supplier
        (nama_supplier,alamat,telepon)

        VALUES

        ('$nama','$alamat','$telepon')"
    );

    header("Location: supplier.php");
    exit;
}

$data = mysqli_query(
    $koneksi,
    "SELECT * FROM supplier
    ORDER BY id_supplier DESC"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">

        <div class="navbar-admin">

            <h4>MotoParts Admin</h4>

            <a href="../dashboard/admin.php"
            class="logout-btn">
                Kembali
            </a>

        </div>

        <div class="container py-5">

            <h1 class="dashboard-title text-center">
                Data Supplier
            </h1>

            <div class="d-flex justify-content-center mb-4">

                <div class="login-card text-center">

                    <h3>Tambah Supplier</h3>

                    <form method="POST">

                        <div class="mb-3">
                            <label>Nama Supplier</label>
                            <input
                            type="text"
                            name="nama_supplier"
                            class="form-control"
                            required>
                        </div>

                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea
                            name="alamat"
                            class="form-control"
                            required></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Telepon</label>
                            <input
                            type="text"
                            name="telepon"
                            class="form-control"
                            required>
                        </div>

                        <button
                        type="submit"
                        name="simpan"
                        class="btn btn-login">
                            Simpan
                        </button>

                    </form>

                </div>

            </div>

            <div class="d-flex justify-content-center">

                <div class="daftar-sparepart-card text-center">

                    <h3>Daftar Supplier</h3>

                    <table class="table table-dark table-hover">

                        <thead>

                            <tr>
                                <th>ID</th>
                                <th>Nama Supplier</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php while($row = mysqli_fetch_assoc($data)){ ?>

                            <tr>

                                <td><?= $row['id_supplier']; ?></td>

                                <td><?= $row['nama_supplier']; ?></td>

                                <td><?= $row['alamat']; ?></td>

                                <td><?= $row['telepon']; ?></td>

                                <td>

                                    <a href="edit_supplier.php?id=<?= $row['id_supplier']; ?>"
                                    class="btn btn-warning btn-sm">
                                    Edit
                                    </a>

                                    <a href="hapus_supplier.php?id=<?= $row['id_supplier']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus supplier ini?')">
                                    Hapus
                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
</body>
</html>