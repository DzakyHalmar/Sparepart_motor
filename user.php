<?php
// Mengaktifkan session dan menyertakan file koneksi database Anda
// session_start();
// include 'koneksi.php'; 

// --- INTEGRASI DATABASE UNTUK USER ---
// Membaca semua data sparepart yang stoknya masih ada (stok > 0)
// $query_produk = mysqli_query($koneksi, "SELECT * FROM sparepart WHERE :stok > 0");

// --- SIMULASI DATA UNTUK VISUAL ---
$products_placeholder = [
    ['id_sparepart' => 1, 'kode_sparepart' => 'ST-001', 'nama_sparepart' => 'Kampas Rem Nissin', 'harga_jual' => 75000, 'stok' => 20],
    ['id_sparepart' => 2, 'kode_sparepart' => 'ST-002', 'nama_sparepart' => 'Oli Shell Advance Ax7', 'harga_jual' => 65000, 'stok' => 15],
    ['id_sparepart' => 3, 'kode_sparepart' => 'ST-003', 'nama_sparepart' => 'V-Belt Kit Honda Beat', 'harga_jual' => 145000, 'stok' => 8],
    ['id_sparepart' => 4, 'kode_sparepart' => 'ST-004', 'nama_sparepart' => 'Busi NGK Iridium', 'harga_jual' => 95000, 'stok' => 12],
    ['id_sparepart' => 5, 'kode_sparepart' => 'ST-005', 'nama_sparepart' => 'Ban Luar Maxxis Victra', 'harga_jual' => 320000, 'stok' => 5],
    ['id_sparepart' => 6, 'kode_sparepart' => 'ST-006', 'nama_sparepart' => 'Lampu LED Osram T19', 'harga_jual' => 55000, 'stok' => 30]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoParts - Toko Sparepart Motor Online</title>

    <!-- Bootstrap 5.3.3 Stabil -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { background:#f5f5f5; }
        .topbar { background:black; color:white; padding:10px 0; }
        .navbar-brand { font-size:30px; font-weight:bold; }
        .brand-red { color:red; }
        .hero { margin-top:20px; }
        .hero-box {
            background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)), url('assets/img/bg-login.jpg');
            background-size:cover; background-position:center; min-height:500px; border-radius:25px;
            display:flex; align-items:center;
        }
        .hero-content { color:white; padding:60px; }
        .hero-content h1 { font-size:70px; font-weight:800; }
        .btn-red { background:red; color:white; border:none; border-radius:30px; padding:12px 30px; transition: 0.2s; }
        .btn-red:hover { background:#cc0000; color:white; }
        .section-title { text-align:center; margin-bottom:40px; font-weight:bold; }
        
        /* Kategori minimalis & interaktif */
        .category-card { transition:0.3s; cursor:pointer; font-weight: 600; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .category-card:hover { transform:translateY(-5px); background: red; color: white; }
        .category-card:hover .bi { color: white !important; }

        /* Card Produk E-Commerce */
        .product-card { border:none; border-radius:20px; overflow:hidden; box-shadow:0 5px 15px rgba(0,0,0,.05); height: 100%; transition: 0.3s; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,.1); }
        .product-card img { height:220px; object-fit:cover; background: #e9ecef; }
        
        .why-us { background:#111; color:white; padding:80px 0; }
        .why-card { background:#1e1e1e; border-radius:20px; padding:30px; text-align:center; height:100%; }
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
                <i class="bi bi-envelope"></i> Hubungi Kami Pemesanan
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
                <li class="nav-item"><a class="nav-link" href="#kategori">Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="#produk-toko">Produk Ready</a></li>
                <li class="nav-item"><a class="nav-link" href="#tentang-kami">Tentang Kami</a></li>
            </ul>
            
            <!-- Info Akun User Terlogin -->
            <div class="d-flex align-items-center">
                <span class="me-3 text-secondary small">
                    <i class="bi bi-person text-danger"></i> Hai, <strong>Pelanggan</strong>
                </span>
                <a href="logout.php" class="btn btn-sm btn-outline-dark rounded-pill px-3">Keluar</a>
            </div>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero container">
    <div class="hero-box">
        <div class="hero-content">
            <h1>Moto<span class="brand-red">Parts</span></h1>
            <p class="fs-3">Solusi Suku Cadang Motor Andal & Original</p>
            <p>Cari kebutuhan part mesin, pelumas, ban, hingga sistem pengereman motormu di sini dengan jaminan harga terbaik.</p>
            <a href="#produk-toko" class="btn btn-red fw-bold px-4 py-2">Belanja Sekarang <i class="bi bi-bag-dash ms-1"></i></a>
        </div>
    </div>
</section>

<!-- KATEGORI -->
<section id="kategori" class="container py-5">
    <h2 class="section-title">Kategori Suku Cadang</h2>
    <div class="row g-3 justify-content-center">
        <?php 
        $categories = [
            ['name' => 'Mesin', 'icon' => 'bi-gear-wide-connected'],
            ['name' => 'Sistem Rem', 'icon' => 'bi-disc'],
            ['name' => 'Oli & Pelumas', 'icon' => 'bi-droplet-half'],
            ['name' => 'Ban & Velg', 'icon' => 'bi-lifecycle'],
            ['name' => 'Kelistrikan', 'icon' => 'bi-lightning-charge'],
            ['name' => 'Aksesoris', 'icon' => 'bi-speedometer2']
        ];
        foreach($categories as $cat): 
        ?>
        <div class="col-md-2 col-6">
            <div class="card category-card text-center p-4">
                <i class="bi <?= $cat['icon']; ?> fs-2 mb-2 text-danger"></i>
                <div><?= $cat['name']; ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- KATALOG PRODUK REAL-TIME (MENGGUNAKAN HARGA_JUAL & STOK DARIDATABASE) -->
<section id="produk-toko" class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Daftar Suku Cadang Ready</h2>
        <span class="badge bg-danger rounded-pill px-3 py-2">Original Guarantee</span>
    </div>
    
    <div class="row g-4">
        <?php foreach($products_placeholder as $product): ?>
        <div class="col-md-4 col-sm-6">
            <div class="card product-card">
                <!-- Gambar default/dummy -->
                <img src="assets/img/bg-login.jpg" class="card-img-top" alt="<?= $product['nama_sparepart']; ?>">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="text-muted small fw-mono"><?= $product['kode_sparepart']; ?></span>
                            <!-- Kondisi Badge Stok -->
                            <?php if($product['stok'] <= 5): ?>
                                <span class="badge bg-warning text-dark">Stok Terbatas</span>
                            <?php else: ?>
                                <span class="badge bg-success">Tersedia</span>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-bold text-dark mb-2"><?= $product['nama_sparepart']; ?></h5>
                        <!-- Menggunakan `harga_jual` dari database -->
                        <h4 class="text-danger fw-bold mb-3">Rp <?= number_format($product['harga_jual'], 0, ',', '.'); ?></h4>
                    </div>
                    
                    <div class="mt-2">
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="small text-muted">Sisa Stok: <strong><?= $product['stok']; ?> unit</strong></span>
                            <!-- Link langsung diarahkan ke chat WhatsApp otomatis untuk order instan -->
                            <a href="https://wa.me/6281234567890?text=Halo%20MotoParts,%20saya%20ingin%20membeli%20<?= urlencode($product['nama_sparepart']); ?>%20(Kode:%20<?= $product['kode_sparepart']; ?>)" 
                               target="_blank" class="btn btn-dark btn-sm rounded-pill px-3">
                               <i class="bi bi-whatsapp me-1 text-success"></i> Hubungi Bengkel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- WHY US -->
<section id="tentang-kami" class="why-us rounded-5 container my-5">
    <div class="container">
        <h2 class="section-title text-white">Standar Pelayanan MotoParts</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="why-card">
                    <i class="bi bi-patch-check text-danger fs-1 mb-3"></i>
                    <h3>100% Produk Original</h3>
                    <p class="text-muted mb-0">Semua sparepart kami disuplai langsung dari distributor resmi merk kendaraan Anda[cite: 3].</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-card">
                    <i class="bi bi-currency-dollar text-danger fs-1 mb-3"></i>
                    <h3>Harga Transparan</h3>
                    <p class="text-muted mb-0">Harga yang tertera di website dijamin sama dengan harga kasir saat Anda berkunjung ke toko kami[cite: 3].</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-card">
                    <i class="bi bi-tools text-danger fs-1 mb-3"></i>
                    <h3>Bisa Sekaligus Pasang</h3>
                    <p class="text-muted mb-0">Beli part online, bawa ke bengkel fisik kami, dan tim mekanik ahli kami siap menginstalasikannya.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="mt-5">
    <div class="container text-center text-muted small">
        <p class="mb-0">&copy; 2026 MotoParts. Halaman Katalog Pelanggan Web Suku Cadang Motor. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>