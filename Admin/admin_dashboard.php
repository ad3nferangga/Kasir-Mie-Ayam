<?php 
include '../koneksi.php';  

$queryMie    = "SELECT * FROM menu WHERE Katagori='mie-ayam' ORDER BY nama_produk ASC"; 
$resultMie   = mysqli_query($conn, $queryMie); 
$queryBakso  = "SELECT * FROM menu WHERE Katagori='bakso'    ORDER BY nama_produk ASC"; 
$resultBakso = mysqli_query($conn, $queryBakso); 
$queryMinum  = "SELECT * FROM menu WHERE Katagori='minuman'  ORDER BY nama_produk ASC"; 
$resultMinum = mysqli_query($conn, $queryMinum); 
?> 

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Menu dan Keranjang</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html { transition: background 0.5s ease, color 0.5s ease; }
    body {
      background-color: #FEF3C7;
      color: #111;
      transition: background .3s, color .3s;
    }
    .dark-mode body {
      background-color: #1F2937 !important;
      color: #E5E7EB !important;
    }
    .dark-mode .text-gray-700 { color: #E5E7EB !important; }
    .dark-mode .text-green-600 { color: #9AE6B4 !important; }
    .dark-mode .bg-white     { background-color: #374151 !important; }
    .dark-mode .bg-gray-100  { background-color: #4B5563 !important; }
    .dark-mode .bg-gray-200  { background-color: #4B5563 !important; }
    .dark-mode .border-gray-300 { border-color: #4B5563 !important; }
    .dark-mode .border-gray-500 { border-color: #4B5563 !important; }
    .dark-mode .border-gray-700 { border-color: #FFFFFF !important; }
    .dark-mode input,
    .dark-mode select {
      background-color: #374151 !important;
      color: #E5E7EB !important;
    }
    .dark-mode input::placeholder,
    .dark-mode select::placeholder {
      color: #A0AEC0 !important;
    }
    .dark-mode input[readonly] {
      background-color: #4B5563 !important;
      color: #CBD5E0 !important;
    }
  </style>
</head>
<body class="bg-orange-100 flex">

  <!-- Sidebar -->
  <div class="w-64 bg-teal-800 text-white min-h-screen p-5 flex flex-col">

    <h2 class="text-2xl font-bold mb-8 text-center">MIE AYAM</h2>

    <ul class="space-y-4 flex-1">
      <li><a href="admin_dashboard.php" class="block p-3 rounded hover:bg-teal-600">Home</a></li>
      <li><a href="Konfirmasi.php"      class="block p-3 rounded hover:bg-teal-600">Pesanan</a></li>
      <li><a href="report.php"          class="block p-3 rounded hover:bg-teal-600">Report</a></li>
      <li><a href="akun.php"            class="block p-3 rounded hover:bg-teal-600">Tambah Akun</a></li>
      <li><a href="add_product.php"     class="block p-3 rounded hover:bg-teal-600">Add Product</a></li>
    </ul>

    <!-- Mode Switch Toggle Horizontal -->
    <div class="mt-6 mb-4 flex items-center justify-center">
      <label for="toggle-mode" class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" id="toggle-mode" class="sr-only peer">
        <div class="w-20 h-10 bg-gray-200 dark:bg-gray-600 rounded-full border border-gray-400 dark:border-gray-600 transition-colors peer-checked:bg-yellow-300"></div>
        <div class="absolute w-8 h-8 bg-white rounded-full top-1 left-1 transition-transform peer-checked:translate-x-10"></div>
        <div class="absolute inset-0 flex items-center justify-between px-2 pointer-events-none">
          <span class="text-yellow-500 text-lg">☀️</span>
          <span class="text-gray-700 dark:text-gray-300 text-lg">🌙</span>
        </div>
      </label>
    </div>

    <a href="../Login.php"
       class="block mt-auto p-3 bg-red-600 rounded hover:bg-red-700 text-center font-semibold shadow-md">
      Logout
    </a>

  </div>

  <!-- Main Content -->
  <div class="container mx-auto p-4 flex-1">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

      <!-- Data Menu -->
      <div class="lg:col-span-2">
        <div class="bg-teal-700 text-white p-4 rounded-t-lg"><h2>🍜 Data Menu</h2></div>
        <div class="bg-white p-4 rounded-b-lg">
          <div class="mb-4">
            <select id="filter-kategori" class="border border-gray-300 p-2 rounded w-full">
              <option value="all">Semua Kategori</option>
              <option value="mie-ayam">Mie Ayam</option>
              <option value="bakso">Bakso</option>
              <option value="minuman">Minuman</option>
            </select>
          </div>

          <?php 
          function renderSection($id, $title, $result) { ?>
            <div id="<?= $id ?>-section" class="menu-section <?= $id ?> bg-white border border-gray-700 rounded-lg p-4 mb-6">
              <div class="text-center mb-4">
                <span class="inline-block bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-full px-6 py-2">
                  <?= $title ?>
                </span>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                  <div class="menu-item bg-white border border-gray-500 rounded shadow overflow-hidden"
                       data-id="<?= $row['id'] ?>"
                       data-nama="<?= htmlspecialchars($row['nama_produk']) ?>"
                       data-harga="<?= $row['price'] ?>">
                    <img src="<?= $row['gambar'] ?>" class="w-full h-40 object-cover">
                    <div class="p-4">
                      <h3 class="text-green-600 font-semibold"><?= htmlspecialchars($row['nama_produk']) ?></h3>
                      <p class="text-green-600">Rp<?= number_format($row['price'],0,',','.') ?>,-</p>
                      <div class="flex space-x-2 mt-2">
                        <button class="tambah-keranjang bg-blue-500 text-white px-4 py-2 rounded flex-1">Tambah</button>
                        <button onclick="location='edit_product.php?id=<?= $row['id'] ?>'"
                                class="bg-yellow-500 text-white px-4 py-2 rounded flex-1">Edit</button>
                        <button class="delete-product bg-red-500 text-white px-4 py-2 rounded flex-1">Hapus</button>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          <?php }
          renderSection('mie-ayam','Mie Ayam',$resultMie);
          renderSection('bakso','Bakso',$resultBakso);
          renderSection('minuman','Minuman',$resultMinum);
          ?>
        </div>
      </div>

      <!-- Keranjang -->
      <div>
        <div class="bg-teal-700 text-white p-4 rounded-t-lg"><h2>🛒 Keranjang</h2></div>
        <div class="bg-white p-4 rounded-b-lg shadow-md">
          <label class="block text-gray-700">Tanggal</label>
          <input type="date" id="tanggal-pesanan" readonly class="w-full p-2 border border-gray-300 rounded mb-4 bg-gray-100 cursor-not-allowed">
          <script>document.getElementById('tanggal-pesanan').value = new Date().toISOString().split('T')[0];</script>

          <label class="block text-gray-700">ATAS NAMA</label>
          <input type="text" id="atas-nama" class="w-full p-2 border border-gray-300 rounded mb-4" placeholder="Atas Nama">

          <label class="block text-gray-700">Metode Pembayaran</label>
          <select id="metode-pembayaran" class="w-full p-2 border border-gray-300 rounded mb-4">
            <option>- Metode Pembayaran -</option>
            <option>Qris</option>
            <option>Cash</option>
          </select>

          <label class="block text-gray-700">Order</label>
          <select id="order" class="w-full p-2 border border-gray-300 rounded mb-4">
            <option>Di Tempat</option>
            <option>Bungkus</option>
          </select>

          <h3 class="text-gray-700 font-semibold mb-2">List Keranjang</h3>
          <table class="w-full mb-4 border-collapse">
            <thead class="bg-gray-100">
              <tr>
                <th class="p-2 border">No</th>
                <th class="p-2 border">Nama</th>
                <th class="p-2 border">Qty</th>
                <th class="p-2 border">Harga</th>
                <th class="p-2 border">#</th>
              </tr>
            </thead>
            <tbody id="keranjang-list"></tbody>
          </table>

          <p class="font-semibold mb-4">Total Bayar: <span id="total-harga">Rp0</span></p>
          <button id="buat-pesanan" class="w-full bg-green-500 text-white p-2 rounded">Buat Pesanan</button>
        </div>
      </div>

    </div>
  </div>

<script>
  // Dark mode toggle
  document.getElementById('toggle-mode').addEventListener('change', function () {
    document.documentElement.classList.toggle('dark-mode', this.checked);
  });

  // Filter kategori
  document.getElementById('filter-kategori').addEventListener('change', function() {
    const kat = this.value;
    document.querySelectorAll('.menu-section').forEach(sec => {
      sec.style.display = (kat === 'all' || sec.classList.contains(kat)) ? 'block' : 'none';
    });
  });

  // Keranjang logic
  let keranjang = [];

  function updateKeranjang() {
    const list = document.getElementById('keranjang-list');
    list.innerHTML = '';
    let total = 0;
    keranjang.forEach((item, idx) => {
      total += item.harga * item.qty;
      const row = document.createElement('tr');
      row.innerHTML = `
        <td class="p-2 border text-center">${idx + 1}</td>
        <td class="p-2 border">${item.nama}</td>
        <td class="p-2 border text-center">
          <button onclick="kurangiQty(${idx})" class="px-2">-</button>
          <span class="px-2">${item.qty}</span>
          <button onclick="tambahQty(${idx})" class="px-2">+</button>
        </td>
        <td class="p-2 border text-right">Rp${(item.harga * item.qty).toLocaleString('id-ID')}</td>
        <td class="p-2 border text-center">
          <button onclick="hapusItem(${idx})">X</button>
        </td>
      `;
      list.appendChild(row);
    });
    document.getElementById('total-harga').textContent = 'Rp' + total.toLocaleString('id-ID');
  }

  function kurangiQty(i) {
    if (keranjang[i].qty > 1) keranjang[i].qty--;
    else keranjang.splice(i, 1);
    updateKeranjang();
  }

  function tambahQty(i) {
    keranjang[i].qty++;
    updateKeranjang();
  }

  function hapusItem(i) {
    keranjang.splice(i, 1);
    updateKeranjang();
  }

  document.querySelectorAll('.tambah-keranjang').forEach(btn => {
    btn.addEventListener('click', () => {
      const itm   = btn.closest('.menu-item'),
            nama  = itm.dataset.nama,
            harga = +itm.dataset.harga;
      const ex = keranjang.find(x => x.nama === nama);
      ex ? ex.qty++ : keranjang.push({ nama, harga, qty: 1 });
      updateKeranjang();
    });
  });

  document.getElementById('buat-pesanan').addEventListener('click', () => {
    if (!keranjang.length) {
      alert('Keranjang kosong!');
      return;
    }
    const namaPemesan = document.getElementById('atas-nama').value.trim();
    if (!namaPemesan) {
      alert('Atas Nama wajib diisi!');
      return;
    }
    const data = new FormData();
    data.append('tanggal', document.getElementById('tanggal-pesanan').value);
    data.append('nama_pemesan', namaPemesan);
    data.append('metode', document.getElementById('metode-pembayaran').value);
    data.append('order', document.getElementById('order').value);
    data.append('keranjang', JSON.stringify(keranjang));

    fetch('simpan_pesanan.php', { method: 'POST', body: data })
      .then(r => r.json())
      .then(res => {
        alert(res.message);
        if (res.status === 'success') {
          keranjang = [];
          updateKeranjang();
          document.getElementById('atas-nama').value = '';
        }
      })
      .catch(console.error);
  });

  document.querySelectorAll('.delete-product').forEach(btn => {
    btn.addEventListener('click', () => {
      if (!confirm('Hapus produk?')) return;
      const menu = btn.closest('.menu-item'),
            id   = menu.dataset.id;
      fetch('delete_product.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id)
      })
      .then(r => r.json())
      .then(d => {
        if (d.success) menu.remove();
        else alert('Gagal: ' + d.message);
      })
      .catch(console.error);
    });
  });
</script>


</body>
</html>