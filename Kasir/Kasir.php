<?php
include '../koneksi.php'; // Pastikan koneksi database benar

// Periksa koneksi database
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query berdasarkan kategori dengan memperhatikan nama kolom "Katagori"
$queryMie = "SELECT * FROM menu WHERE Katagori='mie-ayam' ORDER BY nama_produk ASC";
$resultMie = mysqli_query($conn, $queryMie);

$queryBakso = "SELECT * FROM menu WHERE Katagori='bakso' ORDER BY nama_produk ASC";
$resultBakso = mysqli_query($conn, $queryBakso);

$queryMinum = "SELECT * FROM menu WHERE Katagori='minuman' ORDER BY nama_produk ASC";
$resultMinum = mysqli_query($conn, $queryMinum);

// Cek jika query berhasil dijalankan
if (!$resultMie || !$resultBakso || !$resultMinum) {
    die("Error dalam menjalankan query: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kasir - Menu dan Keranjang</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-100 flex">
  <!-- Sidebar -->
  <div class="w-64 bg-teal-800 text-white min-h-screen p-5 flex flex-col">
    <div class="mb-8 text-center">
      <h2 class="text-2xl font-bold tracking-wide">MIE AYAM</h2>
    </div>
    <ul class="space-y-4 flex-1">
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="Kasir.php">Home</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="Konfirmasi.php">Pesanan</a></li>
    </ul>
    <a class="flex items-center text-lg p-3 bg-red-600 hover:bg-red-700 rounded-lg mt-auto" href="../index.php">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="container mx-auto p-4 flex-1">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      <div class="lg:col-span-2">
        <!-- Header Data Menu -->
        <div class="bg-teal-700 text-white p-4 rounded-t-lg flex items-center">
          <h2 class="text-lg font-semibold">🍜 Data Menu</h2>
        </div>
        <div class="bg-white p-4 rounded-b-lg">
          <!-- Filter Kategori -->
          <div class="mb-4">
            <select id="filter-kategori" class="border border-gray-300 p-2 rounded">
              <option value="all">Semua Kategori</option>
              <option value="mie-ayam">Mie Ayam</option>
              <option value="bakso">Bakso</option>
              <option value="minuman">Minuman</option>
            </select>
          </div>

          <!-- Sections -->
          <?php function renderSection($id, $title, $result) { ?>
            <div id="<?= $id ?>-section" class="border border-gray-700 rounded-lg p-4 mb-6 menu-section <?= $id ?>">
              <div class="text-center mb-4">
                <div class="border border-gray-500 rounded-full px-6 py-2 inline-block text-gray-700 font-semibold bg-gray-200">
                  <?= $title ?>
                </div>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                  <div class="border border-gray-500 rounded-lg shadow-md overflow-hidden menu-item"
                       data-id="<?= $row['id']; ?>"
                       data-nama="<?= htmlspecialchars($row['nama_produk']); ?>"
                       data-harga="<?= $row['price']; ?>">
                    <img src="../Admin/<?= htmlspecialchars($row['gambar']); ?>" class="w-full h-40 object-cover" alt="<?= htmlspecialchars($row['nama_produk']); ?>">
                    <div class="p-4">
                      <h3 class="text-green-600 font-semibold"><?= htmlspecialchars($row['nama_produk']); ?></h3>
                      <p class="text-green-600">Rp<?= number_format($row['price'], 0, ',', '.'); ?>,-</p>
                      <div class="flex space-x-2 mt-2">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded w-full tambah-keranjang">Tambah ke Keranjang</button>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          <?php }
          renderSection('mie-ayam', 'Mie Ayam', $resultMie);
          renderSection('bakso', 'Bakso', $resultBakso);
          renderSection('minuman', 'Minuman', $resultMinum);
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
            <option value="">- Metode Pembayaran -</option>
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
          // Redirect ke halaman Konfirmasi agar data terbaru muncul
          window.location.href = 'Konfirmasi.php';
        }
      })
      .catch(err => {
        console.error(err);
        alert('Gagal membuat pesanan. Coba lagi.');
      });
  });
  </script>
</body>
</html>
