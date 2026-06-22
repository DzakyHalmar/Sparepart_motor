<?php
include '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id_kategori = $_GET['id'];
    
    // Eksekusi hapus data berdasarkan ID
    $query = mysqli_query($koneksi, "DELETE FROM kategori WHERE id_kategori = '$id_kategori'");
    
    if ($query) {
        echo "<script>alert('Kategori berhasil dihapus!'); window.location='kategori.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus! Kategori ini mungkin masih terikat dengan data sparepart.'); window.location='kategori.php';</script>";
    }
} else {
    header("Location: kategori.php");
}
?>