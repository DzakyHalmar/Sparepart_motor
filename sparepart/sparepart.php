
<?php

    include '../config/koneksi.php';

    $kategori = mysqli_query(
        $koneksi,
        "SELECT * FROM kategori"
    );

    $pesan = "";

    if(isset($_POST['simpan'])){

        $id_kategori = $_POST['id_kategori'];
        $kode = $_POST['kode_sparepart'];
        $nama = $_POST['nama_sparepart'];
        $beli = $_POST['harga_beli'];
        $jual = $_POST['harga_jual'];
        $stok = $_POST['stok'];

        mysqli_query($koneksi,
        "INSERT INTO sparepart
        (id_kategori,kode_sparepart,nama_sparepart,harga_beli,harga_jual,stok)

        VALUES

        ('$id_kategori','$kode','$nama','$beli','$jual','$stok')");

        $pesan = "Data sparepart berhasil ditambahkan!";
    }

    $data = mysqli_query(
        $koneksi,
        "SELECT
            sparepart.*,
            kategori.nama_kategori

        FROM sparepart

        LEFT JOIN kategori
        ON sparepart.id_kategori = kategori.id_kategori

        ORDER BY id_sparepart DESC"
    );

?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sparepart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <div class="dashboard">

        <div class="navbar-admin">
            <h4>MotoParts Admin</h4>

            <a href="../dashboard/admin.php" class="logout-btn">
                Kembali
            </a>
        </div>

        <div class="container py-5">

            <h1 class="dashboard-title text-center">
                Data Sparepart
            </h1>

            <?php 
                if($pesan != ""){ 
                    ?>
                        <div class="alert alert-success">
                            <?= $pesan ?>
                        </div>
                    <?php } 
            ?>

            <!-- CARD TAMBAH SPAREPART -->
            <div class="d-flex justify-content-center">

                <div class="login-card mb-4 text-center">

                    <h3>Tambah Sparepart</h3>

                    <form method="POST">

                        <div class="mb-3">
                            <label>Kode Sparepart</label>
                            <input
                                type="text"
                                name="kode_sparepart"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Nama Sparepart</label>
                            <input
                                type="text"
                                name="nama_sparepart"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">

                            <label>Kategori</label>

                            <select
                            name="id_kategori"
                            class="form-control"
                            required>

                                <option value="">
                                    -- Pilih Kategori --
                                </option>

                                <?php while($k = mysqli_fetch_assoc($kategori)){ ?>

                                    <option value="<?= $k['id_kategori']; ?>">
                                        <?= $k['nama_kategori']; ?>
                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <div class="mb-3">
                            <label>Harga Beli</label>
                            <input
                                type="number"
                                name="harga_beli"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Harga Jual</label>
                            <input
                                type="number"
                                name="harga_jual"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Stok</label>
                            <input
                                type="number"
                                name="stok"
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

            <!-- CARD DAFTAR SPAREPART -->
            <div class="d-flex justify-content-center">

                <div class="daftar-sparepart-card  text-center">

                    <h3>Daftar Sparepart</h3>

                    <table class="table table-dark table-hover">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php while($row = mysqli_fetch_assoc($data)){ ?>

                            <tr>
                                <td><?= $row['id_sparepart']; ?></td>
                                <td><?= $row['kode_sparepart']; ?></td>
                                <td><?= $row['nama_sparepart']; ?></td>
                                <td><?= $row['nama_kategori']; ?></td>
                                <td>Rp <?= number_format($row['harga_beli']); ?></td>
                                <td>Rp <?= number_format($row['harga_jual']); ?></td>
                                <td><?= $row['stok']; ?></td>

                                <td>
                                    <!-- button edit -->
                                    <a href="edit_sparepart.php?id=<?= $row['id_sparepart']; ?>"
                                    class="btn btn-warning btn-sm">
                                    Edit
                                    </a>

                                    <!-- button hapus -->
                                    <a href="hapus_sparepart.php?id=<?= $row['id_sparepart']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">
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
