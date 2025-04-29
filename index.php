<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if ($password == $user['password']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: Admin/admin_dashboard.php");
            } elseif ($user['role'] == 'kasir') {
                header("Location: Kasir/Kasir.php");
            }
            exit();
        }
    }
    echo "<script>alert('Login gagal! Username atau password salah.'); window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Kasir Mie Ayam</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="min-h-screen bg-gradient-to-br from-teal-500 to-blue-600 flex items-center justify-center transition-colors duration-300">

  <div class="flex w-full max-w-4xl bg-white shadow-2xl rounded-lg overflow-hidden">
    <!-- Left Side -->
    <div class="w-1/2 bg-teal-800 p-10 flex flex-col justify-center items-center text-center text-white">
      <h2 class="text-5xl font-bold mb-6">MIE AYAM</h2>
      <img src="assets/gambarlogin-removebg-preview.png" alt="Gambar Mie Ayam" class="w-48 h-48 object-cover rounded-full shadow-lg mb-6 bg-white p-2">
      <p class="text-xl font-light">Nikmati kemudahan memesan mie ayam dengan sistem kasir modern.</p>
    </div>
    <!-- Right Side -->
    <div class="w-1/2 p-10 flex flex-col justify-center">
      <h2 class="text-4xl font-bold text-gray-800 dark:text-white mb-8">Login</h2>
      <form method="POST" action="index.php" class="space-y-6">
        <div>
          <label class="block text-gray-600 dark:text-gray-300 text-lg mb-2">Username</label>
          <input type="text" name="username" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter your username" required>
        </div>
        <div>
          <label class="block text-gray-600 dark:text-gray-300 text-lg mb-2">Password</label>
          <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="w-full bg-teal-600 text-white py-3 rounded-lg hover:bg-teal-700 transition duration-200 font-semibold">
          Login <i class="fas fa-sign-in-alt ml-2"></i>
        </button>
      </form>
    </div>
  </div>
</body>
</html>
