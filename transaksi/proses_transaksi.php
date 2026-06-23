<?php

session_start();
if(
    empty($_POST['id_sparepart']) ||
    empty($_POST['qty']) ||
    empty($_POST['tgl_transaksi'])
){
    die("Data transaksi tidak lengkap.");
}
include '../config/koneksi.php';

$id_sparepart = $_POST['id_sparepart'];
$qty          = $_POST['qty'];
$tanggal      = $_POST['tgl_transaksi'];

$id_user = $_SESSION['id_user'];

/* ambil data sparepart */
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM sparepart
     WHERE id_sparepart='$id_sparepart'"
);

$data = mysqli_fetch_assoc($query);

if(!$data){
    die("Data sparepart tidak ditemukan.");
}

$harga = $data['harga_jual'];
$stok  = $data['stok'];

if($qty > $stok){
    echo "<script>
            alert('Stok tidak mencukupi');
            window.history.back();
          </script>";
    exit;
}

/* hitung total */
$total = $harga * $qty;

/* simpan penjualan */
$simpan = mysqli_query(
    $koneksi,
    "INSERT INTO penjualan
    (tanggal,id_user,total)
    VALUES
    ('$tanggal','$id_user','$total')"
);

if(!$simpan){
    die("Gagal menyimpan transaksi : " . mysqli_error($koneksi));
}

/* update stok */
mysqli_query(
    $koneksi,
    "UPDATE sparepart
     SET stok = stok - $qty
     WHERE id_sparepart = '$id_sparepart'"
);

echo "<script>
        alert('Transaksi berhasil disimpan');
        window.location='../dashboard/kasir.php';
      </script>";