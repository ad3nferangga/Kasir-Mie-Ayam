<?php
// Sertakan file koneksi ke database
include '../koneksi.php'; // Pastikan koneksi.php berisi koneksi yang benar

// Periksa apakah form telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan koneksi ke database ada
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Ambil data dari form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Tidak di-hash
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Validasi input tidak boleh kosong
    if (empty($username) || empty($password) || empty($role)) {
        echo "<script>alert('Semua kolom harus diisi!'); window.location.href='add_account.php';</script>";
        exit();
    }

    // Query untuk menambahkan data ke tabel user
    $sql = "INSERT INTO user (username, password, role) VALUES ('$username', '$password', '$role')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Akun berhasil ditambahkan!'); window.location.href='akun.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Tutup koneksi database
    mysqli_close($conn);
}
?>
