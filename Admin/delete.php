<?php
include '../koneksi.php'; // Sesuaikan path ke file koneksi Anda

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM kasir WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='konfirmasi.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location.href='konfirmasi.php';</script>";
    }
} else {
    // Jika parameter id tidak ada, kembali ke konfirmasi.php
    echo "<script>window.location.href='konfirmasi.php';</script>";
}
?>
