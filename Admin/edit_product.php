<?php
include '../koneksi.php';

$id = $_GET['id'];
// Ambil data produk berdasarkan ID
$result = mysqli_query($conn, "SELECT * FROM menu WHERE id=$id");
$produk = mysqli_fetch_assoc($result);

if (!$produk) {
    die("Produk dengan ID $id tidak ditemukan!");
}

// Jika form disubmit
if (isset($_POST['update'])) {
  $nama_produk = $_POST['nama_produk'];
  $price = $_POST['price'];
  
  // Jika pengguna mengunggah gambar baru, lakukan upload dan update field gambar
  if (!empty($_FILES['gambar']['name'])) {
    $imageName = basename($_FILES["gambar"]["name"]);
    $targetDir = "uploads/"; // Folder uploads berada di Admin/uploads/
    $newImagePath = $targetDir . time() . "_" . $imageName;
    
    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $newImagePath)) {
      // (Opsional) Hapus gambar lama jika diinginkan:
      // if(file_exists($produk['gambar'])) { unlink($produk['gambar']); }
      $updateQuery = "UPDATE menu SET nama_produk='$nama_produk', price='$price', gambar='$newImagePath' WHERE id=$id";
    } else {
      die("Upload gambar gagal.");
    }
  } else {
    $updateQuery = "UPDATE menu SET nama_produk='$nama_produk', price='$price' WHERE id=$id";
  }
  
  mysqli_query($conn, $updateQuery);
  header("Location: admin_dashboard.php");
  exit;
}
?>
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
  <div class="flex-1 container mx-auto p-4">
    <div class="bg-white w-full max-w-lg mx-auto p-6 rounded shadow-lg">
      <h2 class="text-2xl font-bold mb-4 text-center text-teal-700">Edit Produk</h2>
      <!-- Tampilkan gambar saat ini -->
      <div class="mb-4 text-center">
        <img src="<?= htmlspecialchars($produk['gambar']); ?>" class="w-48 h-48 object-cover mx-auto border" alt="<?= htmlspecialchars($produk['nama_produk']); ?>">
      </div>
      <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <!-- Nama Produk -->
        <div>
          <label class="block text-gray-700 font-semibold mb-1" for="nama_produk">Nama Produk</label>
          <input 
            type="text" 
            name="nama_produk" 
            id="nama_produk" 
            class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:ring-teal-300"
            value="<?= htmlspecialchars($produk['nama_produk']); ?>" 
            required
          />
        </div>
        <!-- Harga -->
        <div>
          <label class="block text-gray-700 font-semibold mb-1" for="price">Harga</label>
          <input 
            type="number" 
            name="price" 
            id="price" 
            class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:ring-teal-300"
            value="<?= htmlspecialchars($produk['price']); ?>" 
            required
          />
        </div>
        <!-- Upload Gambar Baru (Opsional) -->
        <div>
          <label class="block text-gray-700 font-semibold mb-1" for="gambar">Ganti Gambar</label>
          <input 
            type="file" 
            name="gambar" 
            id="gambar" 
            class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring focus:ring-teal-300"
            accept="image/*"
          />
          <p class="text-gray-500 text-sm mt-1">Kosongkan jika tidak ingin mengganti gambar</p>
        </div>
        <div class="flex justify-end mt-4">
          <button 
            type="submit" 
            name="update"
            class="bg-teal-600 text-white px-5 py-2 rounded hover:bg-teal-700 transition"
          >
            Update
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
