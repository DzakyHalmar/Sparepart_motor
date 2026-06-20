<?php
// blom ngubungin ke sql nya
// cnth: include 'config/koneksi.php';

$total_sparepart = 142;
$total_transaksi = 48;
$total_kategori  = 6;

$products = [
    ['id' => 1, 'nama' => 'Kampas Rem Nissin Vario', 'harga' => 75000, 'stok' => 20, 'gambar' => 'assets/img/bg-login.jpg'],
    ['id' => 2, 'nama' => 'Oli Shell Advance Ax7', 'harga' => 65000, 'stok' => 15, 'gambar' => 'assets/img/bg-login.jpg'],
    ['id' => 3, 'nama' => 'V-Belt Kit Dan Roller Beat', 'harga' => 145000, 'stok' => 8, 'gambar' => 'assets/img/bg-login.jpg']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoParts - Dashboard User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { background:#f5f5f5; }
        .topbar { background:black; color:white; padding:10px 0; }
        .navbar-brand { font-size:30px; font-weight:bold; }
        .brand-red { color:red; }
        .hero { margin-top:20px; }
        .hero-box {
            background: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)), url('assets/img/bg-login.jpg');
            background-size:cover; background-position:center; min-height:500px; border-radius:25px;
            display:flex; align-items:center;
        }
        .hero-content { color:white; padding:60px; }
        .hero-content h1 { font-size:70px; font-weight:800; }
        .btn-red { background:red; color:white; border:none; border-radius:30px; padding:12px 30px; transition: 0.2s; }
        .btn-red:hover { background:#cc0000; color:white; }
        .section-title { text-align:center; margin-bottom:40px; font-weight:bold; }
        .category-card { transition:0.3s; cursor:pointer; font-weight: 600; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .category-card:hover { transform:translateY(-8px); background: red; color: white; }
        .product-card { border:none; border-radius:20px; overflow:hidden; box-shadow:0 3px 10px rgba(0,0,0,.1); height: 100%; }
        .product-card img { height:220px; object-fit:cover; }
        .why-us { background:#111; color:white; padding:80px 0; }
        .why-card { background:#1e1e1e; border-radius:20px; padding:30px; text-align:center; height:100%; }
        .dashboard-card { border-radius:20px; text-align:center; padding:25px; color:white; height: 100%; }
        .bg-red { background:red; }
        footer { background:#111; color:white; padding:50px 0; }
        footer ul { list-style:none; padding:0; }
        footer ul li { margin-bottom:10px; }
    </style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <i class="bi bi-whatsapp"></i> +62 812-3456-7890 &nbsp;&nbsp;
                <i class="bi bi-envelope"></i> motoparts@gmail.com
            </div>
            <div class="col-md-6 text-end">
                <i class="bi bi-instagram me-3"></i>
                <i class="bi bi-facebook me-3"></i>
                <i class="bi bi-tiktok"></i>
            </div>
        </div>
    </div>
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">Moto<span class="brand-red">Parts</span></a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#dashboard-panel">Ringkasan</a></li>
                <li class="nav-item"><a class="nav-link" href="#produk-unggulan">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="#input-transaksi">Transaksi Baru</a></li>
                <li class="nav-item"><a class="nav-link" href="#filter-laporan">Laporan</a></li>
            </ul>
            <!-- Navigasi Hak Akses Logout -->
            <a href="auth/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                <i class="bi bi-box-arrow-right me-1"></i> Log Out
            </a>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero container">
    <div class="hero-box">
        <div class="hero-content">
            <h1>Moto<span class="brand-red">Parts</span></h1>
            <p class="fs-3">Solusi Sparepart Motor Berkualitas</p>
            <p>Menyediakan berbagai sparepart motor original dan aftermarket dengan harga terbaik.</p>
            <a href="#input-transaksi" class="btn btn-red fw-bold">Mulai Transaksi <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
    </div>
</section>

<!-- POIN 5: DASHBOARD USER PANEL (RINGKASAN DATA KELOLAAN) -->
<section id="dashboard-panel" class="container py-5">
    <h2 class="section-title">Dashboard Ringkasan Data</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="dashboard-card bg-dark shadow-sm">
                <h1 class="display-4 fw-bold text-warning"><?= $total_sparepart; ?></h1>
                <p class="text-uppercase tracking-wider mb-0">Jumlah Data Master Suku Cadang</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card bg-red shadow-sm">
                <h1 class="display-4 fw-bold"><?= $total_transaksi; ?></h1>
                <p class="text-uppercase tracking-wider mb-0">Total Transaksi Penjualan</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card bg-success shadow-sm">
                <h1 class="display-4 fw-bold"><?= $total_kategori; ?></h1>
                <p class="text-uppercase tracking-wider mb-0">Kategori Terdaftar</p>
            </div>
        </div>
    </div>
</section>

<!-- KATEGORI -->
<section class="container py-4">
    <h2 class="section-title">Kategori Suku Cadang</h2>
    <div class="row g-4 justify-content-center">
        <?php 
        $categories = ['Mesin', 'Sistem Rem', 'Oli & Pelumas', 'Ban & Velg', 'Kelistrikan / Lampu', 'Aksesoris'];
        foreach($categories as $cat): 
        ?>
        <div class="col-md-2 col-6">
            <div class="card category-card text-center p-4">
                <i class="bi bi-nut fs-3 mb-2 text-danger"></i><br><?= $cat; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- POIN 4 & 6: PRODUK UNGGULAN (READ DATA MASTER) -->
<section id="produk-unggulan" class="container py-5">
    <h2 class="section-title">Katalog Sparepart</h2>
    <div class="row g-4">
        <?php foreach($products as $product): ?>
        <div class="col-md-4">
            <div class="card product-card">
                <img src="<?= $product['gambar']; ?>" class="card-img-top" alt="Suku Cadang">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold"><?= $product['nama']; ?></h5>
                        <h4 class="text-danger fw-bold">Rp <?= number_format($product['harga'], 0, ',', '.'); ?></h4>
                        <p class="text-muted">Sisa Stok : <span class="badge bg-secondary"><?= $product['stok']; ?> pcs</span></p>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-dark w-100 mb-2"><i class="bi bi-info-circle me-1"></i> Detail Kode Part</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- POIN 6: FORM TRANSAKSI BARU (MELIBATKAN LEBIH DARI 2 TABEL) -->
<section id="input-transaksi" class="container py-5 bg-white rounded-4 shadow-sm my-5 p-4">
    <h2 class="section-title">Input Transaksi Penjualan</h2>
    <form action="transaksi/proses_transaksi.php" method="POST">
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-bold">No. Nota / Transaksi</label>
                <input type="text" class="form-control bg-light" name="no_nota" value="TRX-<?= date('Ymd-His'); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Tanggal Transaksi</label>
                <input type="date" class="form-control" name="tgl_transaksi" value="<?= date('Y-m-d'); ?>" required>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Pilih Barang Master</th>
                        <th style="width: 150px;">Jumlah Beli</th>
                        <th style="width: 200px;">Keterangan Pembeli</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select class="form-select" name="id_sparepart" required>
                                <option value="">-- Pilih Suku Cadang Terdaftar --</option>
                                <?php foreach($products as $p): ?>
                                    <option value="<?= $p['id']; ?>"><?= $p['nama']; ?> (Rp <?= number_format($p['harga'], 0, ',', '.'); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="qty" min="1" value="1" required>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="pelanggan" placeholder="Nama Konsumen" required>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-red px-5 fw-bold"><i class="bi bi-cart-check me-2"></i>Simpan Transaksi</button>
        </div>
    </form>
</section>

<!-- POIN 7: LAPORAN BERDASARKAN PERIODE -->
<section id="filter-laporan" class="container py-5 bg-dark text-white rounded-4 my-5 p-4">
    <div class="row align-items-center">
        <div class="col-md-4">
            <h3 class="fw-bold text-danger"><i class="bi bi-file-earmark-pdf-fill me-2"></i>Laporan Penjualan</h3>
            <p class="text-muted mb-md-0">Cetak dokumen rekonsiliasi transaksi berdasarkan periode tanggal pilihan.</p>
        </div>
        <div class="col-md-8">
            <form action="laporan/cetak_laporan.php" method="GET" target="_blank">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small text-uppercase tracking-wider">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tgl_awal" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small text-uppercase tracking-wider">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tgl_akhir" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-red w-100 fw-bold"><i class="bi bi-printer"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- WHY US -->
<section class="why-us">
    <div class="container">
        <h2 class="section-title text-white">Kenapa Memilih Kami?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="why-card">
                    <i class="bi bi-shield-check text-danger fs-1 mb-3"></i>
                    <h3>Original Product</h3>
                    <p class="text-muted">Produk suku cadang terdistribusi resmi dan bergaransi pabrik.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-card">
                    <i class="bi bi-tags text-danger fs-1 mb-3"></i>
                    <h3>Harga Terjangkau</h3>
                    <p class="text-muted">Skema harga eceran tertinggi yang kompetitif untuk semua tipe motor.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-card">
                    <i class="bi bi-truck text-danger fs-1 mb-3"></i>
                    <h3>Pengiriman Cepat</h3>
                    <p class="text-muted">Integrasi kurir logistik instan untuk menjamin ketersediaan bengkel.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h4 class="fw-bold">Moto<span class="brand-red">Parts</span></h4>
                <p class="text-muted">Platform pemenuhan suku cadang andalan mekanik dan pengendara.</p>
            </div>
            <div class="col-md-2">
                <h5>Menu Utama</h5>
                <ul class="text-muted">
                    <li>Beranda</li>
                    <li>Katalog Produk</li>
                    <li>Sistem Transaksi</li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Kontak Admin</h5>
                <ul class="text-muted">
                    <li><i class="bi bi-telephone me-2"></i>Whatsapp CS</li>
                    <li><i class="bi bi-envelope me-2"></i>Email Support</li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Jam Operasional</h5>
                <ul class="text-muted">
                    <li>Senin - Sabtu</li>
                    <li>08.00 - 20.00 WIB</li>
                </ul>
            </div>
        </div>
        <hr class="mt-4 border-secondary">
        <div class="text-center text-muted mt-2 small">
            &copy; 2026 MotoParts. UAS Pemrograman Web 1. All Rights Reserved.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>