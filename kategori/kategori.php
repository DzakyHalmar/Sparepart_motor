<?php

    session_start();

    // 1. Jika belum login sama sekali, tendang ke login
    if(!isset($_SESSION['id_user'])){
        header("Location: ../auth/login.php");
        exit;
    }

    // 2. PROTEKSI BARU: Hanya ADMIN yang boleh masuk ke manajemen kategori
    if($_SESSION['role'] !== 'admin'){
        // Jika kasir nekat akses lewat URL, lempar balik ke dashboard kasir
        header("Location: ../dashboard/kasir.php");
        exit;
    }

    include '../config/koneksi.php';

    if(isset($_POST['simpan'])){

        $nama = $_POST['nama_kategori'];

        mysqli_query(
            $koneksi,
            "INSERT INTO kategori(nama_kategori)
            VALUES('$nama')"
        );

        header("Location: kategori.php");
        exit;
    }

    $data = mysqli_query(
        $koneksi,
        "SELECT * FROM kategori
        ORDER BY id_kategori DESC"
    );

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kategori</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

    <div class="dashboard">

        <div class="navbar-admin d-flex justify-content-between align-items-center px-4">

            <h4>MotoParts Admin</h4>

            <div class="navbar-menu">
                <a href="../dashboard/kasir.php" class="text-white text-decoration-none me-3">
                    <i class="fas fa-cash-register me-1"></i> POS Kasir
                </a>
                
                <?php if ($_SESSION['role'] == 'admin') : ?>
                    <a href="kategori.php" class="text-danger text-decoration-none fw-bold me-3">
                        <i class="fas fa-tags me-1"></i> Kelola Kategori
                    </a>
                <?php endif; ?>
                
                <a href="../auth/logout.php" class="text-warning text-decoration-none">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>

            <a href="../dashboard/admin.php" class="logout-btn">
                Kembali
            </a>

        </div>

        <div class="container py-5">

            <h1 class="dashboard-title text-center" >
                Data Kategori
            </h1>

            <div class="d-flex justify-content-center">

                <div class="login-card mb-4">

                    <h3>Tambah Kategori</h3>

                    <form method="POST">

                        <div class="mb-3">

                            <label>Nama Kategori</label>

                            <input
                            type="text"
                            name="nama_kategori"
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

                <div class="login-card text-center">

                    <h3>Daftar Kategori</h3>

                    <table class="table table-dark table-hover">

                        <thead>

                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php while($row = mysqli_fetch_assoc($data)){ ?>

                            <tr>

                                <td><?= $row['id_kategori']; ?></td>

                                <td><?= $row['nama_kategori']; ?></td>

                                <td>

                                    <a href="edit_kategori.php?id=<?= $row['id_kategori']; ?>"
                                    class="btn btn-warning btn-sm">
                                    Edit
                                    </a>

                                    <a href="hapus_kategori.php?id=<?= $row['id_kategori']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus kategori ini?')">
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