<?php
session_start();

// 1. PERBAIKAN JALUR KONEKSI: Cuma mundur 1 folder (../) bukan 2 folder!
include '../config/koneksi.php'; 

// Variabel untuk menampung status halaman status/tampilan struk
$sukses = false;
$error_msg = "";
$detail_transaksi = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $nama_pembeli = mysqli_real_escape_string($koneksi, $_POST['nama_pembeli']);

    $id_sparepart = $_POST['id_sparepart'];
    $qty = $_POST['qty'];

    $grand_total = 0;
    $detail_barang = [];

    for ($i = 0; $i < count($id_sparepart); $i++) {

        $idBarang = $id_sparepart[$i];
        $jumlah = intval($qty[$i]);

        if($jumlah <= 0){
            continue;
        }

        $query_sp = mysqli_query(
            $koneksi,
            "SELECT * FROM sparepart WHERE id_sparepart='$idBarang'"
        );

        $sparepart = mysqli_fetch_assoc($query_sp);

        if (!$sparepart) {
            continue;
        }

        if ($jumlah > $sparepart['stok']) {
            $error_msg = "Stok ".$sparepart['nama_sparepart']." tidak mencukupi.";
            break;
        }

        $subtotal =
            $sparepart['harga_jual']
            *
            $jumlah;

        $grand_total += $subtotal;

        $detail_barang[] = [
            'id_sparepart' => $idBarang,
            'nama'         => $sparepart['nama_sparepart'],
            'harga'        => $sparepart['harga_jual'],
            'qty'          => $jumlah,
            'subtotal'     => $subtotal,
            'stok'         => $sparepart['stok']
        ];
    }
    if(empty($error_msg)){

            mysqli_query(
            $koneksi,
            "INSERT INTO penjualan
            (
                tanggal,
                id_user,
                nama_pembeli,
                total
            )
            VALUES
            (
                '$tanggal',
                '$id_user',
                '$nama_pembeli',
                '$grand_total'
            )"
        );

        // Ambil ID transaksi yang baru dibuat
        $id_penjualan = mysqli_insert_id($koneksi);

        // Simpan semua barang ke tabel detail_penjualan
        foreach($detail_barang as $item){

            mysqli_query(
                $koneksi,
                "INSERT INTO detail_penjualan
                (
                    id_penjualan,
                    id_sparepart,
                    qty,
                    harga,
                    subtotal
                )
                VALUES
                (
                    '$id_penjualan',
                    '".$item['id_sparepart']."',
                    '".$item['qty']."',
                    '".$item['harga']."',
                    '".$item['subtotal']."'
                )"
            );

            // Kurangi stok
            mysqli_query(
                $koneksi,
                "UPDATE sparepart
                SET stok = stok - ".$item['qty']."
                WHERE id_sparepart='".$item['id_sparepart']."'"
            );
        }

        $sukses = true;

        $detail_transaksi = [
            'nota'    => 'INV-' . time(),
            'tanggal' => $tanggal,
            'items'   => $detail_barang,
            'total'   => $grand_total
        ];
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
        body { 
            background: #f5f5f5; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        .receipt-card { 
            max-width: 500px; 
            margin: 60px auto; 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            overflow: hidden; 
        }
        .receipt-header { 
            background: #111; 
            color: white; 
            padding: 30px; 
            text-align: center; 
            position: relative; 
        }
        .receipt-header .brand { 
            font-size: 28px; 
            font-weight: bold; 
        }
        .receipt-header .brand span { 
            color: red; 
        }
        .receipt-body { 
            background: white; 
            padding: 30px; 
        }
        .line-dashed { 
            border-top: 2px dashed #ddd; 
            margin: 20px 0; 
        }
        .btn-red { 
            background: red; 
            color: white; 
            border-radius: 30px; 
            padding: 10px 25px; 
            font-weight: bold; 
            transition: 0.2s; 
            border: none;
        }
        .btn-red:hover { 
            background: #cc0000; 
            color: white; 
        }
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

                <div class="mt-2 mb-3">
                    <strong>Nama Pembeli :</strong>
                    <?= htmlspecialchars($nama_pembeli); ?>
                </div>
                
                <div class="line-dashed"></div>
                
                <div class="mb-2 small text-uppercase tracking-wider text-muted fw-bold">Detail Item:</div>
                <?php foreach($detail_transaksi['items'] as $item): ?>

                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div style="max-width:70%;">
                        <h6 class="fw-bold mb-0">
                            <?= $item['nama']; ?>
                        </h6>

                        <small class="text-muted">
                            Rp <?= number_format($item['harga'],0,',','.'); ?>
                            x <?= $item['qty']; ?>
                        </small>
                    </div>

                    <span class="fw-bold">
                        Rp <?= number_format($item['subtotal'],0,',','.'); ?>
                    </span>

                </div>

                <?php endforeach; ?>
                
                <div class="line-dashed"></div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fs-5 fw-bold text-dark">TOTAL CASH</span>
                    <span class="fs-4 fw-bold text-danger">Rp <?= number_format($detail_transaksi['total'], 0, ',', '.'); ?></span>
                </div>
                
                <div class="alert alert-secondary small text-center mb-4 py-2">
                    <i class="bi bi-info-circle me-1"></i>
                    Semua stok barang berhasil diperbarui secara otomatis.
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