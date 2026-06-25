<?php
session_start();
include '../config/koneksi.php';

$tgl_awal  = isset($_GET['tgl_awal']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_awal']) : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_akhir']) : date('Y-m-d');

// ==================== REVISI QUERY 1 (TABEL LAPORAN) ====================
// Menghubungkan penjualan, detail_penjualan, dan sparepart
$sql = "SELECT
            p.tanggal,
            s.nama_sparepart,
            d.qty,
            d.subtotal
        FROM penjualan p
        JOIN detail_penjualan d
            ON p.id_penjualan = d.id_penjualan
        JOIN sparepart s
            ON d.id_sparepart = s.id_sparepart
        WHERE p.tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'
        ORDER BY p.tanggal ASC";
$query = mysqli_query($koneksi, $sql);

$laporan_data = [];
$total_omset = 0;

if ($query && mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $laporan_data[] = $row;
        // Hitung total omset dari kolom subtotal di detail_penjualan
        $total_omset += $row['subtotal']; 
    }
}

$qNota = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS jumlah
     FROM penjualan
     WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'"
);

$jumlahNota = mysqli_fetch_assoc($qNota)['jumlah'];


// ==================== REVISI QUERY 2 (GRAFIK CHART) ====================
// Mengambil total pendapatan harian dari tabel penjualan
$sql_chart = "SELECT
                p.tanggal,
                SUM(d.subtotal) AS total_harian
            FROM penjualan p
            JOIN detail_penjualan d
                ON p.id_penjualan = d.id_penjualan
            WHERE p.tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'
            GROUP BY p.tanggal
            ORDER BY p.tanggal";
$query_chart = mysqli_query($koneksi, $sql_chart);

$chart_labels = [];
$chart_data = [];

if ($query_chart && mysqli_num_rows($query_chart) > 0) {
    while ($c = mysqli_fetch_assoc($query_chart)) {
        $chart_labels[] = date('d M', strtotime($c['tanggal']));
        $chart_data[] = $c['total_harian'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoParts - Laporan Omset Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .report-header { background: #111; color: white; padding: 30px; border-radius: 0 0 20px 20px; }
        .brand span { color: red; font-weight: bold; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-red { background: red; color: white; border-radius: 30px; padding: 8px 25px; border: none; font-weight: bold;}
        .btn-red:hover { background: #cc0000; color: white; }
        
        /* CSS Khusus Cetak / Print PDF */
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .card-custom { box-shadow: none; border: 1px solid #ccc; }
        }
    </style>
</head>
<body>

<div class="report-header shadow-sm mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h2 class="brand mb-0">Moto<span>Parts</span></h2>
            <p class="small text-white-100 mb-2">Rekapitulasi Omset Penjualan POS Kasir</p>
            <small class="text-danger fw-bold">Periode: <?= date('d M Y', strtotime($tgl_awal)); ?> s/d <?= date('d M Y', strtotime($tgl_akhir)); ?></small>
        </div>
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-red me-2"><i class="bi bi-file-earmark-pdf me-2"></i>Cetak ke PDF / Print</button>
            <a href="../dashboard/kasir.php" class="btn btn-outline-light rounded-pill"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        </div>
    </div>
</div>

<div class="container py-3">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card card-custom bg-white p-4 h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-graph-up-arrow text-danger me-2"></i>Tren Pendapatan Harian</h5>
                <hr>
                <?php if(!empty($chart_data)): ?>
                    <canvas id="omsetChart" style="max-height: 320px;"></canvas>
                <?php else: ?>
                    <div class="text-center text-muted py-5">Tidak ada data grafik untuk periode ini.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card card-custom bg-dark text-white p-4 mb-4">
                <div class="row align-items-center">
                    <div class="col-7">
                        <h6 class="text-white-100 small">Total Omset Pendapatan</h6>
                        <h2 class="fw-bold text-warning mb-0">Rp <?= number_format($total_omset,0,',','.'); ?></h2>
                    </div>
                    <div class="col-5 text-end">
                        <span class="badge bg-danger fs-6 rounded-pill"><?= $jumlahNota; ?> Nota Terbit</span>
                    </div>
                </div>
            </div>

            <div class="card card-custom bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-ul text-danger me-2"></i>Rincian Riwayat Transaksi</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle small">
                        <thead class="table-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Suku Cadang</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($laporan_data)): ?>
                                <?php foreach($laporan_data as $data): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($data['tanggal'])); ?></td>
                                    <td class="fw-bold"><?= $data['nama_sparepart']; ?></td>
                                    <td class="text-center"><?= $data['qty']; ?> pcs</td>
                                    <td class="text-end fw-bold text-danger">Rp <?= number_format($data['subtotal'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada transaksi pada periode tanggal ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('omsetChart');
    if(ctx) {
        new Chart(ctx, {
            type: 'bar', // Bisa diganti 'line' kalau mau grafik garis
            data: {
                labels: <?= json_encode($chart_labels); ?>,
                datasets: [{
                    label: 'Omset (Rp)',
                    data: <?= json_encode($chart_data); ?>,
                    backgroundColor: 'rgba(255, 0, 0, 0.7)', // Merah MotoParts
                    borderColor: 'rgb(204, 0, 0)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }
                        }
                    }
                }
            }
        });
    }
</script>

</body>
</html>