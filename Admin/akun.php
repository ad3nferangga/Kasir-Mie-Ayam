<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Menu dan Keranjang</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-100 flex">
  <!-- Sidebar -->
  <div class="w-64 bg-teal-800 text-white min-h-screen p-5 flex flex-col">
    <div class="mb-8 text-center">
      <h2 class="text-2xl font-bold tracking-wide">MIE AYAM</h2>
    </div>
    <ul class="space-y-4 flex-1">
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="admin_dashboard.php">Home</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="Konfirmasi.php">pesanan</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="report.php">Report</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="akun.php">Tambah Akun</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="add_product.php">Add Product</a></li>
      <li><a href="about.php" class="block p-3 rounded hover:bg-teal-600">About</a></li>
    </ul>
    <a class="flex items-center text-lg p-3 bg-red-600 hover:bg-red-700 rounded-lg mt-auto" href="../index.php">
      Logout
    </a>
  </div>
   <!-- Main Content -->
   <div class="flex-1 p-4">
    <div class="container mx-auto p-4">
     <div class="bg-white p-6 rounded-lg shadow-lg">
      <h2 class="text-2xl font-semibold mb-4">
       Add Account
      </h2>
      <form action="add_account.php" method="POST">
    <div class="mb-4">
        <label class="block text-gray-700 mb-2" for="username">Username</label>
        <input class="border border-gray-300 rounded px-4 py-2 w-full" id="username" name="username" placeholder="Enter username" type="text" required/>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 mb-2" for="password">Password</label>
        <input class="border border-gray-300 rounded px-4 py-2 w-full" id="password" name="password" placeholder="Enter password" type="password" required/>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 mb-2" for="role">Role</label>
        <select class="border border-gray-300 rounded px-4 py-2 w-full" id="role" name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="Admin">Admin</option>
            <option value="Kasir">Kasir</option>
        </select>
    </div>
    <div>
        <button class="bg-teal-700 text-white px-4 py-2 rounded" type="submit">Add Account</button>
    </div>
</form>
     </div>
    </div>
   </div>
  </div>
 </body>
</html>
