<?php
session_start();

// 1. Proteksi Keamanan: Jika belum login, tendang ke halaman login
if(!isset($_SESSION['id_user'])){
    header("Location: ../auth/login.php");
    exit;
}

// 2. Proteksi Role: Jika yang masuk bukan kasir, tendang ke halaman admin
if($_SESSION['role'] != 'kasir'){
    header("Location: admin.php");
    exit;
}

// 3. Hubungkan ke database (Mundur 1 folder untuk mencari folder config)
include '../config/koneksi.php'; 

// --- 1. AMBIL DATA SUMMARY SECARA DINAMIS DARI DATABASE ---
// Hitung jumlah data master suku cadang
$query_sp = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM sparepart");
$data_sp = mysqli_fetch_assoc($query_sp);
$total_sparepart = isset($data_sp['total']) ? $data_sp['total'] : 0;

// Hitung total transaksi penjualan yang pernah dilakukan
$query_pj = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM penjualan");
$data_pj = mysqli_fetch_assoc($query_pj);
$total_transaksi = isset($data_pj['total']) ? $data_pj['total'] : 0;

// Hitung jumlah kategori unik langsung dari tabel master kategori
$query_kt = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kategori");
$data_kt = mysqli_fetch_assoc($query_kt);
// Jika tabel kategori masih kosong di database, default ke angka 6 sesuai template visual
$total_kategori = (isset($data_kt['total']) && $data_kt['total'] > 0) ? $data_kt['total'] : 6;


// --- 2. AMBIL DAFTAR BARANG MASTER DENGAN RELASI KATEGORI ---
$products = [];
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$sql = "SELECT sparepart.*, kategori.nama_kategori 
        FROM sparepart 
        LEFT JOIN kategori ON sparepart.id_kategori = kategori.id_kategori";

// Jika ada filter kategori terpilih, tambahkan klausa WHERE
if (!empty($filter_kategori)) {
    $sql .= " WHERE kategori.nama_kategori = '" . mysqli_real_escape_string($koneksi, $filter_kategori) . "'";
}

$query_produk = mysqli_query($koneksi, $sql);

if ($query_produk) {
    while ($row = mysqli_fetch_assoc($query_produk)) {
        $products[] = $row;
    }
}

// Jika database masih kosong, sediakan placeholder dummy agar tampilan template tidak pecah/kosong saat pertama kali run
if (empty($products)) {
    $products = [
        ['id_sparepart' => 1, 'kode_sparepart' => 'SP-01', 'nama_sparepart' => 'Kampas Rem Nissin Vario', 'harga_jual' => 75000, 'stok' => 20, 'nama_kategori' => 'Sistem Rem'],
        ['id_sparepart' => 2, 'kode_sparepart' => 'SP-02', 'nama_sparepart' => 'Oli Shell Advance Ax7', 'harga_jual' => 65000, 'stok' => 15, 'nama_kategori' => 'Oli & Pelumas'],
        ['id_sparepart' => 3, 'kode_sparepart' => 'SP-03', 'nama_sparepart' => 'V-Belt Kit Dan Roller Beat', 'harga_jual' => 145000, 'stok' => 8, 'nama_kategori' => 'Mesin']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoParts - Dashboard Kasir POS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { background:#f5f5f5; }
        .topbar { background:black; color:white; padding:10px 0; }
        .topbar a {
            transition: color 0.2s ease-in-out;
            font-size: 0.9rem;
        }

        .topbar a:hover {
            color: #dc3545 !important;
        }

        .social-icon {
            font-size: 1rem;
        }
        .navbar-brand { font-size:30px; font-weight:bold; }
        .brand-red { color:red; }
        .hero { margin-top:20px; }
        .hero-box {
            background: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)), url('../assets/img/bg-login.jpg');
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
        footer p { list-style:none; padding:0; color: white;}
        footer ul { list-style:none; padding:0; color: white;}
        footer ul li { margin-bottom:10px; color: white;}
    </style>
</head>
<body>

<div class="topbar bg-dark py-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <a href="https://wa.me/6281290323200" target="_blank" class="text-decoration-none text-light me-3">
                    <i class="bi bi-whatsapp text-success me-1"></i> +62 812-9032-3200
                </a>
                <a href="mailto:motoparts@gmail.com" class="text-decoration-none text-light">
                    <i class="bi bi-envelope text-danger me-1"></i> motoparts@gmail.com
                </a>
            </div>
            
            <div class="col-md-6 text-center text-md-end">
                <a href="https://instagram.com" target="_blank" class="text-light me-3"><i class="bi bi-instagram social-icon"></i></a>
                <a href="https://facebook.com" target="_blank" class="text-light me-3"><i class="bi bi-facebook social-icon"></i></a>
                <a href="https://tiktok.com" target="_blank" class="text-light"><i class="bi bi-tiktok social-icon"></i></a>
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">Moto<span class="brand-red">Parts</span></a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="kasir.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#dashboard-panel">Ringkasan</a></li>
                <li class="nav-item"><a class="nav-link" href="#produk-unggulan">Stok Monitor</a></li>
                <li class="nav-item"><a class="nav-link" href="#input-transaksi">Input POS Kasir</a></li>
                <li class="nav-item"><a class="nav-link" href="#filter-laporan">Laporan</a></li>
            </ul>
            <div class="navbar-text me-3 text-dark fw-bold small">
                <i class="bi bi-person-badge text-danger"></i> Login: Kasir Toko
            </div>
            <a href="auth/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                <i class="bi bi-box-arrow-right me-1"></i> Log Out
            </a>
        </div>
    </div>
</nav>

<section class="hero container">
    <div class="hero-box">
        <div class="hero-content">
            <h1>Moto<span class="brand-red">Parts</span></h1>
            <p class="fs-3">Sistem POS Kasir & Pemantauan Suku Cadang</p>
            <p>Panel Workspace terintegrasi untuk melayani checkout pesanan customer fisik bengkel secara real-time.</p>
            <a href="#input-transaksi" class="btn btn-red fw-bold">Buka Meja Kasir <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
    </div>
</section>

<section id="dashboard-panel" class="container py-5">
    <h2 class="section-title">Dashboard Analisis Kasir</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="dashboard-card bg-dark shadow-sm">
                <h1 class="display-4 fw-bold text-warning"><?= $total_sparepart; ?></h1>
                <p class="text-uppercase tracking-wider mb-0">Total SKU Master Suku Cadang</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card bg-red shadow-sm">
                <h1 class="display-4 fw-bold"><?= $total_transaksi; ?></h1>
                <p class="text-uppercase tracking-wider mb-0">Nota Penjualan Terbit</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card bg-success shadow-sm">
                <h1 class="display-4 fw-bold"><?= $total_kategori; ?></h1>
                <p class="text-uppercase tracking-wider mb-0">Kelompok Kategori Aktif</p>
            </div>
        </div>
    </div>
</section>

<section class="container py-4">
    <h2 class="section-title">Kategori Suku Cadang</h2>
    <div class="row g-4 justify-content-center">
        <div class="col-md-2 col-6">
            <a href="kasir.php#produk-unggulan" class="text-decoration-none text-dark">
                <div class="card category-card text-center p-4 <?= empty($filter_kategori) ? 'bg-danger text-white' : ''; ?>">
                    <i class="bi bi-grid-fill fs-3 mb-2"></i><br>Semua Barang
                </div>
            </a>
        </div>

        <?php 
        $categories = ['Mesin', 'Sistem Rem', 'Oli & Pelumas', 'Ban & Velg', 'Kelistrikan / Lampu', 'Aksesoris'];
        foreach($categories as $cat): 
            $is_active = ($filter_kategori == $cat) ? 'bg-danger text-white' : '';
        ?>
        <div class="col-md-2 col-6">
            <a href="kasir.php?kategori=<?= urlencode($cat); ?>#produk-unggulan" class="text-decoration-none text-dark">
                <div class="card category-card text-center p-4 <?= $is_active; ?>">
                    <i class="bi bi-nut fs-3 mb-2 text-danger"></i><br><?= $cat; ?>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="produk-unggulan" class="container py-5">
    <h2 class="section-title">Katalog & Info Stok Gudang</h2>
    <div class="row g-4">
        <?php foreach($products as $product): ?>
        <div class="col-md-4">
            <div class="card product-card">
                <?php 
                // Menentukan gambar berdasarkan kategori produk
                $gambar = 'mesin.jpg.jpg'; // Gambar cadangan jika tidak cocok
                $kategori_produk = isset($product['Sistem Rem']) ? $product['Sistem Rem'] : '';

                if (strpos($kategori_produk, 'Oli') !== false) {
                    $gambar = 'oli.jpg.jpg';
                } elseif (strpos($kategori_produk, 'Rem') !== false) {
                    $gambar = 'kampas_rem.jpg.jpg';
                } elseif (strpos($kategori_produk, 'Ban') !== false) {
                    $gambar = 'ban.jpg.jpg';
                } elseif (strpos($kategori_produk, 'Mesin') !== false) {
                    $gambar = 'mesin.jpg.jpg';
                }
                ?>
                <img src="../assets/img/<?= $gambar; ?>" class="card-img-top" alt="Suku Cadang" style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="mb-1"><span class="badge bg-dark"><?= isset($product['kode_sparepart']) ? $product['kode_sparepart'] : 'SP-X'; ?></span></div>
                        <h5 class="fw-bold"><?= isset($product['nama_sparepart']) ? $product['nama_sparepart'] : $product['nama']; ?></h5>
                        <h4 class="text-danger fw-bold">Rp <?= number_format(isset($product['harga_jual']) ? $product['harga_jual'] : $product['harga'], 0, ',', '.'); ?></h4>
                        
                        <p class="text-muted mt-2">
                            Sisa Stok : 
                            <?php if($product['stok'] <= 5): ?>
                                <span class="badge bg-danger">Kritis: <?= $product['stok']; ?> pcs</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= $product['stok']; ?> pcs</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-dark btn-sm w-100" disabled>
                            <i class="bi bi-tag-fill me-1"></i> Kategori: <?= isset($product['kategori']) ? $product['kategori'] : 'Umum'; ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="input-transaksi" class="container py-5 bg-white rounded-4 shadow-sm my-5 p-4">
    <h2 class="section-title">Input Penjualan Kasir (POS)</h2>
    <form action="transaksi/proses_transaksi.php" method="POST">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-bold">Tanggal Transaksi</label>
                <input type="date" class="form-control" name="tanggal" value="<?= date('Y-m-d'); ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">ID User/Operator</label>
                <input type="text" class="form-control bg-light" name="id_user" value="2" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Status Layanan</label>
                <input type="text" class="form-control bg-light" value="Bayar Di Tempat (Cash)" readonly>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Pilih Suku Cadang (Database Master)</th>
                        <th style="width: 180px;">Jumlah Beli (`qty`)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select class="form-select" name="id_sparepart" required>
                                <option value="">-- Pilih Item Suku Cadang --</option>
                                <?php foreach($products as $p): ?>
                                    <?php 
                                        $id = isset($p['id_sparepart']) ? $p['id_sparepart'] : $p['id'];
                                        $nama_item = isset($p['nama_sparepart']) ? $p['nama_sparepart'] : $p['nama'];
                                        $harga_item = isset($p['harga_jual']) ? $p['harga_jual'] : $p['harga'];
                                    ?>
                                    <option value="<?= $id; ?>" <?= ($p['stok'] < 1) ? 'disabled' : ''; ?>>
                                        <?= $nama_item; ?> (Rp <?= number_format($harga_item, 0, ',', '.'); ?>) 
                                        <?= ($p['stok'] < 1) ? '- [STOK HABIS]' : '- (Sisa '.$p['stok'].')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control" name="qty" min="1" value="1" required>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-red px-5 fw-bold"><i class="bi bi-cart-check me-2"></i>Simpan & Kurangi Stok</button>
        </div>
    </form>
</section>

<section id="filter-laporan" class="container py-5 bg-dark text-white rounded-4 my-5 p-4">
    <div class="row align-items-center">
        <div class="col-md-4">
            <h3 class="fw-bold text-danger"><i class="bi bi-file-earmark-pdf-fill me-2"></i>Laporan Omset Penjualan</h3>
            <p class="text-muted mb-md-0">Cetak rekapitulasi nota kasir berdasarkan filter periode untuk diserahkan ke Admin.</p>
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

<section class="why-us">
    <div class="container">
        <h2 class="section-title text-white">Standar Operasional POS</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="why-card">
                    <i class="bi bi-shield-check text-danger fs-1 mb-3"></i>
                    <h3>Data Terpusat</h3>
                    <p class="text-white-50">Setiap perubahan stok oleh Admin otomatis meng-update sistem kasir detik itu juga.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-card">
                    <i class="bi bi-tags text-danger fs-1 mb-3"></i>
                    <h3>Validasi Harga</h3>
                    <p class="text-white-50">Akurasi harga jual diambil langsung dari record database sparepart ter-update.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-card">
                    <i class="bi bi-lightning text-danger fs-1 mb-3"></i>
                    <h3>Transaksi Instan</h3>
                    <p class="text-white-50">Prosedur transaksi dirancang sesederhana mungkin untuk meminimalkan antrean di kasir bengkel.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h4 class="fw-bold">Moto<span class="brand-red">Parts</span></h4>
                <p>Platform pemenuhan suku cadang andalan mekanik dan pengendara.</p>
            </div>
            <div class="col-md-2">
                <h5>Workspace</h5>
                <ul class="text-muted">
                    <li>Beranda</li>
                    <li>Stok Gudang</li>
                    <li>POS Kasir</li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Kontak Pusat</h5>
                <ul class="text-muted">
                    <li><i class="bi bi-telephone me-2"></i>Internal Extension (102)</li>
                    <li><i class="bi bi-envelope me-2"></i>it-support@motoparts.com</li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Shift Kerja Kasir</h5>
                <ul class="text-muted">
                    <li>Senin - Sabtu</li>
                    <li>08.00 - 20.00 WIB</li>
                </ul>
            </div>
        </div>
        <hr class="mt-4 border-secondary">
        <div class="text-center text-muted mt-2 small">
            <p>&copy; 2026 MotoParts. UAS Pemrograman Web 1. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>