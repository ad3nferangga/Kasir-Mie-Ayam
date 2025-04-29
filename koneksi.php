<?php
$host = "mysql.railway.internal"; // Sesuaikan dengan host database
$user = "root"; // Sesuaikan dengan username database
$password = "EmxQSDLjWbFJkHBzuiyfdUOAmjqzYOBd"; // Sesuaikan dengan password database
$database = "railway"; // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
