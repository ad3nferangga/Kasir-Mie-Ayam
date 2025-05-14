<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Mie Ayam Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#ffe9cc] min-h-screen flex">
  <!-- Sidebar -->
  <aside class="bg-[#16675d] w-64 text-white min-h-screen p-5 flex flex-col rounded-tr-lg rounded-br-lg">
    <h2 class="text-2xl font-bold mb-8 text-center">MIE AYAM</h2>

    <ul class="space-y-4 flex-1">
      <li><a href="admin_dashboard.php" class="block p-3 rounded hover:bg-teal-600">Home</a></li>
      <li><a href="Konfirmasi.php" class="block p-3 rounded hover:bg-teal-600">Pesanan</a></li>
      <li><a href="report.php" class="block p-3 rounded hover:bg-teal-600">Report</a></li>
      <li><a href="akun.php" class="block p-3 rounded hover:bg-teal-600">Tambah Akun</a></li>
      <li><a href="add_product.php" class="block p-3 rounded hover:bg-teal-600">Add Product</a></li>
      <li><a href="about.php" class="block p-3 rounded hover:bg-teal-600">About</a></li>
    </ul>

    <a href="../Login.php" class="block mt-auto p-3 bg-red-600 rounded hover:bg-red-700 text-center font-semibold shadow-md">
      Logout
    </a>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-4">
    <section class="bg-[#16675d] text-white font-semibold rounded-t-md px-4 py-3 mb-2">
      About Data Pembuat Apk
    </section>
    <section class="bg-white bg-opacity-30 rounded-b-md px-4 py-4 space-y-4 max-w-3xl">
      <div>
        <h2 class="font-semibold text-sm mb-2">About Data Pembuat Apk</h2>
        <p class="text-sm text-gray-900">
          Aplikasi ini dibuat oleh seorang pengembang yang berdedikasi untuk membantu mengelola menu dan pesanan Mie Ayam secara efisien. Ini menyediakan fitur untuk menambahkan produk baru, mengelola pesanan, menghasilkan laporan, dan mengelola akun pengguna. Tujuannya adalah untuk memperlancar operasi bisnis dan meningkatkan kepuasan pelanggan.
        </p>
      </div>
      <div>
        <h2 class="font-semibold text-sm mb-2">Data Diri Pembuat</h2>
        <ul class="text-sm text-gray-900 list-disc list-inside space-y-1">
          <li><strong>Name:</strong> M.Aden feranggga</li>
          <li><strong>Email:</strong> Mohammadadenferangga@Gmail.com</li>
          <li><strong>Phone:</strong>  0812 3456 7890</li>
          <li><strong>Address:</strong> Gresik</li>
        </ul>
        <ul class="text-sm text-gray-900 list-disc list-inside space-y-1 mt-4">
          <li><strong>Name:</strong> Febriyan Firman P</li>
          <li><strong>Address:</strong> Sidoarjo</li>
          <li><strong>Phone:</strong> 081217271109</li>
          <li><strong>Email:</strong> febriyanfirmanpraditi@gmail.com</li>
        </ul>
        <ul class="text-sm text-gray-900 list-disc list-inside space-y-1 mt-4">
          <li><strong>Name:</strong> Devany Nadia Prasetyo</li>
          <li><strong>Address:</strong> Nganjuk</li>
          <li><strong>Phone:</strong> 085812099925</li>
          <li><strong>Email:</strong> devanynadia@gmail.com</li>
        </ul>
        <ul class="text-sm text-gray-900 list-disc list-inside space-y-1 mt-4">
          <li><strong>Name:</strong> Amalia Zahrah Abidah</li>
          <li><strong>Address:</strong> Sidoarjo</li>
          <li><strong>Phone:</strong> 083841663770</li>
          <li><strong>Email:</strong> zahrabdhamelia@gmail.com</li>
        </ul>
      </div>
    </section>
  </main>
</body>
</html>
