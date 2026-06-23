<?php
session_start();

// 1. PERBAIKAN JALUR KONEKSI: Cuma mundur 1 folder (../) bukan 2 folder!
include '../config/koneksi.php'; 

// Variabel untuk menampung status halaman status/tampilan struk
$sukses = false;
$error_msg = "";
$detail_transaksi = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 2. Tangkap data dari form kasir
    $tanggal      = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $id_user      = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $id_sparepart = mysqli_real_escape_string($koneksi, $_POST['id_sparepart']);
    $qty          = intval($_POST['qty']);

    // 3. Ambil data produk untuk cek stok & hitung harga
    $query_sp = mysqli_query($koneksi, "SELECT * FROM sparepart WHERE id_sparepart = '$id_sparepart'");
    $sparepart = mysqli_fetch_assoc($query_sp);

    if ($sparepart) {
        $nama_barang = $sparepart['nama_sparepart'];
        $harga_jual  = $sparepart['harga_jual'];
        $stok_sekarang = $sparepart['stok'];
        $total_bayar = $harga_jual * $qty;

        // Validasi kecukupan stok gudang
        if ($stok_sekarang >= $qty) {
            
            // A. Insert ke tabel penjualan
            $sql_insert = "INSERT INTO penjualan (tanggal, id_user, total, id_sparepart, qty, total_harga)
               VALUES ('$tanggal', '$id_user', '$total_bayar', '$id_sparepart', '$qty', '$total_bayar')";
            
            if (mysqli_query($koneksi, $sql_insert)) {
                
                // B. Potong stok sparepart
                $stok_baru = $stok_sekarang - $qty;
                mysqli_query($koneksi, "UPDATE sparepart SET stok = '$stok_baru' WHERE id_sparepart = '$id_sparepart'");

                // Set flag sukses & simpan detail buat tampilin struk belanjaan
                $sukses = true;
                $detail_transaksi = [
                    'nota' => 'INV-' . time(),
                    'tanggal' => $tanggal,
                    'barang' => $nama_barang,
                    'harga' => $harga_jual,
                    'qty' => $qty,
                    'total' => $total_bayar
                ];

            } else {
                $error_msg = "Gagal menyimpan ke database: " . mysqli_error($koneksi);
            }
        } else {
            $error_msg = "Stok Gudang Tidak Cukup! Sisa stok saat ini hanya " . $stok_sekarang . " pcs.";
        }
    } else {
        $error_msg = "Data Suku Cadang Tidak Ditemukan di Database Master!";
    }
} else {
    // Kalau diakses langsung tanpa klik tombol simpan, usir balik ke kasir
    header("Location: ../kasir.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoParts - Status Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f5f5f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .receipt-card { max-width: 500px; margin: 60px auto; border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; }
        .receipt-header { background: #111; color: white; padding: 30px; text-align: center; position: relative; }
        .receipt-header .brand { font-size: 28px; font-weight: bold; }
        .receipt-header .brand span { color: red; }
        .receipt-body { background: white; padding: 30px; }
        .line-dashed { border-top: 2px dashed #ddd; margin: 20px 0; }
        .btn-red { background: red; color: white; border-radius: 30px; padding: 10px 25px; font-weight: bold; transition: 0.2s; border: none;}
        .btn-red:hover { background: #cc0000; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="card receipt-card">
        <?php if ($sukses): ?>
            <!-- JIKA TRANSAKSI BERHASIL: TAMPILIN STRUK KASIR MOTOPARTS -->
            <div class="receipt-header">
                <div class="brand">Moto<span>Parts</span></div>
                <p class="small text-muted mb-0 mt-1">Sistem Point of Sales (POS)</p>
                <div class="mt-3"><i class="bi bi-check-circle-fill text-success fs-1"></i></div>
                <h5 class="mt-2 text-success fw-bold">TRANSAKSI BERHASIL!</h5>
            </div>
            
            <div class="receipt-body">
                <div class="d-flex justify-content-between small text-muted">
                    <span>No. Nota: <?= $detail_transaksi['nota']; ?></span>
                    <span><?= date('d M Y', strtotime($detail_transaksi['tanggal'])); ?></span>
                </div>
                
                <div class="line-dashed"></div>
                
                <div class="mb-2 small text-uppercase tracking-wider text-muted fw-bold">Detail Item:</div>
                <div class="d-flex justify-content-between align-items-start">
                    <div style="max-width: 70%;">
                        <h6 class="fw-bold mb-0"><?= $detail_transaksi['barang']; ?></h6>
                        <small class="text-muted">Rp <?= number_format($detail_transaksi['harga'], 0, ',', '.'); ?> x <?= $detail_transaksi['qty']; ?></small>
                    </div>
                    <span class="fw-bold">Rp <?= number_format($detail_transaksi['total'], 0, ',', '.'); ?></span>
                </div>
                
                <div class="line-dashed"></div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fs-5 fw-bold text-dark">TOTAL CASH</span>
                    <span class="fs-4 fw-bold text-danger">Rp <?= number_format($detail_transaksi['total'], 0, ',', '.'); ?></span>
                </div>
                
                <div class="alert alert-secondary small text-center mb-4 py-2">
                    <i class="bi bi-info-circle me-1"></i> Stok di database gudang otomatis terpotong **<?= $detail_transaksi['qty']; ?> pcs**.
                </div>

                <div class="text-center">
                    <a href="../dashboard/kasir.php" class="btn btn-red w-100"><i class="bi bi-arrow-left me-2"></i>Kembali ke Meja Kasir</a>
                </div>
            </div>

        <?php else: ?>
            <!-- JIKA TRANSAKSI GAGAL: TAMPILIN ERROR BOX -->
            <div class="receipt-header bg-dark">
                <div class="brand">Moto<span>Parts</span></div>
                <div class="mt-3"><i class="bi bi-exclamation-triangle-fill text-danger fs-1"></i></div>
                <h5 class="mt-2 text-danger fw-bold">TRANSAKSI GAGAL!</h5>
            </div>
            
            <div class="receipt-body text-center">
                <div class="alert alert-danger my-3">
                    <?= $error_msg; ?>
                </div>
                <p class="text-muted small">Silakan periksa kembali ketersediaan stok atau hubungi IT support jika masalah berlanjut.</p>
                <div class="line-dashed"></div>
                <a href="../dashboard/kasir.php" class="btn btn-dark w-100 rounded-pill"><i class="bi bi-arrow-left me-2"></i>Kembali & Perbaiki</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>