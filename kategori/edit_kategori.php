<?php
include '../config/koneksi.php';

// Ambil data lama berdasarkan ID yang diklik
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($koneksi, "SELECT * FROM kategori WHERE id_kategori = '$id'");
    $row = mysqli_fetch_assoc($query);
}

// Proses update ketika tombol simpan diklik
if (isset($_POST['update'])) {
    $id_kategori = $_POST['id_kategori'];
    $nama_kategori = $_POST['nama_kategori'];
    
    $update = mysqli_query($koneksi, "UPDATE kategori SET nama_kategori = '$nama_kategori' WHERE id_kategori = '$id_kategori'");
    
    if ($update) {
        echo "<script>alert('Kategori berhasil diubah!'); window.location='kategori.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-center">
            <div class="card p-4 shadow-sm" style="width: 400px; background:#212529; color:white;">
                <h3>Edit Nama Kategori</h3>
                <hr>
                <form method="POST">
                    <input type="hidden" name="id_kategori" value="<?= $row['id_kategori']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori Baru</label>
                        <input type="text" name="nama_kategori" class="form-control" value="<?= $row['nama_kategori']; ?>" required>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="kategori.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" name="update" class="btn btn-warning">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>