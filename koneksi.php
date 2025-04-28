<?php
$host = "localhost"; // Sesuaikan dengan host database
$user = "root"; // Sesuaikan dengan username database
$password = ""; // Sesuaikan dengan password database
$database = "kasir_mieayam"; // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
