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
    </ul>
    <a class="flex items-center text-lg p-3 bg-red-600 hover:bg-red-700 rounded-lg mt-auto" href="../Login.php">
      Logout
    </a>
  </div>

  <!-- Menu Section -->
  <div class="w-full px-6 py-4">
    <div class="bg-teal-700 text-white p-4 rounded-t-lg flex items-center">
      <h2 class="text-lg font-semibold">Data Menu</h2>
    </div>
    <div class="bg-white p-4 rounded-b-lg">
      <div id="menu-items" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Produk yang sudah ada akan muncul di sini -->
      </div>

      <!-- Form Tambah Produk -->
      <div class="mt-4">
        <h3 class="text-lg font-semibold mb-2">Add New Product</h3>
        <div class="flex items-center space-x-2">
          <input id="new-product-name" class="border border-gray-300 rounded px-4 py-2" placeholder="Product Name" type="text"/>
          <input id="new-product-price" class="border border-gray-300 rounded px-4 py-2" placeholder="Price" type="number"/>
          <input id="new-product-image" class="border border-gray-300 rounded px-4 py-2" type="file" accept="image/*"/>
          
          <!-- Dropdown Kategori (gunakan "katagori" agar sesuai dengan backend) -->
          <select id="new-product-katagori" class="border border-gray-300 rounded px-4 py-2">
            <option value="mie-ayam">Mie Ayam</option>
            <option value="bakso">Bakso</option>
            <option value="minuman">Minuman</option>
          </select>
          
          <button class="bg-green-500 text-white px-4 py-2 rounded" onclick="addNewProduct()">Add Product</button>
          
        </div>
      </div>
    </div>
  </div>

  <script>
    function addNewProduct() {
      const name = document.getElementById('new-product-name').value.trim();
      const price = document.getElementById('new-product-price').value.trim();
      const imageInput = document.getElementById('new-product-image').files[0];
      const katagori = document.getElementById('new-product-katagori').value; // Ambil kategori

      if (!name || !price || !imageInput) {
          alert('Please fill in all fields and select an image');
          return;
      }

      const formData = new FormData();
      formData.append('name', name);
      formData.append('price', price);
      formData.append('image', imageInput);
      formData.append('katagori', katagori); // Kirim data kategori

      fetch('save_product.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              // Tambahkan produk baru ke area "menu-items" tanpa reload
              const menuItems = document.getElementById('menu-items');
              menuItems.innerHTML += `
                  <div class="border rounded-lg overflow-hidden">
                      <img class="w-full h-32 object-cover" src="${data.image}" alt="${name}"/>
                      <div class="p-4">
                          <h3 class="text-green-600 font-semibold">${name}</h3>
                          <p class="text-green-600">Rp${parseInt(price).toLocaleString()},-</p>
                          <p class="text-gray-500 text-sm italic">Kategori: ${katagori}</p>
                      </div>
                  </div>
              `;
              // Reset form input
              document.getElementById('new-product-name').value = '';
              document.getElementById('new-product-price').value = '';
              document.getElementById('new-product-image').value = '';
          } else {
              alert('Failed: ' + data.message);
          }
      })
      .catch(error => console.error('Error:', error));
    }
  </script>

</body>
</html>
