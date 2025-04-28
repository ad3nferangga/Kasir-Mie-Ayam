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
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="Kasir.php">Home</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="Konfirmasi.php">pesanan</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="report.php">Report</a></li>
    </ul>
    <a class="flex items-center text-lg p-3 bg-red-600 hover:bg-red-700 rounded-lg mt-auto" href="../Login.php">Logout</a>
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
          <!-- Filter Kategori (opsional) -->
          <div class="mb-4">
            <select id="filter-kategori" class="border border-gray-300 p-2 rounded">
              <option value="all">Semua Kategori</option>
              <option value="mie-ayam">Mie Ayam</option>
              <option value="bakso">Bakso</option>
              <option value="minuman">Minuman</option>
            </select>
          </div>

          <!-- Mie Ayam Section -->
          <div id="mie-ayam-section" class="border border-gray-700 rounded-lg p-4 mb-6 menu-section mie-ayam">
            <div class="text-center mb-4">
              <div class="border border-gray-500 rounded-full px-6 py-2 inline-block text-gray-700 font-semibold bg-gray-200">
                Mie Ayam
              </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
              <?php while($row = mysqli_fetch_assoc($resultMie)) { ?>
                <div class="border border-gray-500 rounded-lg shadow-md overflow-hidden menu-item"
                     data-id="<?= $row['id']; ?>"
                     data-nama="<?= htmlspecialchars($row['nama_produk']); ?>"
                     data-harga="<?= $row['price']; ?>">
                  <!-- Perbaiki path gambar -->
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

          <!-- Bakso Section -->
          <div id="bakso-section" class="border border-gray-700 rounded-lg p-4 mb-6 menu-section bakso">
            <div class="text-center mb-4">
              <div class="border border-gray-500 rounded-full px-6 py-2 inline-block text-gray-700 font-semibold bg-gray-200">
                Bakso
              </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
              <?php while($row = mysqli_fetch_assoc($resultBakso)) { ?>
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

          <!-- Minuman Section -->
          <div id="minuman-section" class="border border-gray-700 rounded-lg p-4 menu-section minuman">
            <div class="text-center mb-4">
              <div class="border border-gray-500 rounded-full px-6 py-2 inline-block text-gray-700 font-semibold bg-gray-200">
                Minuman
              </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
              <?php while($row = mysqli_fetch_assoc($resultMinum)) { ?>
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

          <!-- Form Tambah Produk dihapus -->
        </div>
      </div>

      <!-- Keranjang -->
      <div>
        <div class="bg-teal-700 text-white p-4 rounded-t-lg">
          <h2 class="text-lg font-semibold">🛒 Keranjang</h2>
        </div>
        <div class="bg-white p-4 rounded-b-lg shadow-md">
          <!-- Input Tanggal -->
          <label class="block text-gray-700">Tanggal</label>
          <input type="date" id="tanggal-pesanan" class="border border-gray-300 rounded px-4 py-2 w-full mb-4" readonly />
          <!-- Input Nama Pemesan -->
          <label class="block text-gray-700">ATAS NAMA</label>
          <!-- Tambahkan id "atas-nama" di sini -->
          <input type="text" id="atas-nama" class="border border-gray-300 rounded px-4 py-2 w-full mb-4" placeholder="Atas Nama"/>
          <h3 class="text-gray-700 font-semibold mb-2">List Keranjang</h3>
          <table class="min-w-full border text-left border-gray-300 mb-4">
            <thead class="bg-gray-100">
              <tr>
                <th class="border px-4 py-2">No</th>
                <th class="border px-4 py-2">Nama</th>
                <th class="border px-4 py-2">Qty</th>
                <th class="border px-4 py-2">Harga</th>
                <th class="border px-4 py-2">#</th>
              </tr>
            </thead>
            <tbody id="keranjang-list"></tbody>
          </table>
          <label class="block text-gray-700">Metode</label>
          <select class="border border-gray-300 rounded px-4 py-2 w-full mb-4">
            <option>- Metode Pembayaran -</option>
            <option>Qris</option>
            <option>Cash</option>
          </select>
          <label class="block text-gray-700">Order</label>
          <select class="border border-gray-300 rounded px-4 py-2 w-full mb-4">
            <option>Di Tempat</option>
            <option>Bungkus</option>
          </select>
          <p class="mt-4 font-semibold">Total Bayar: <span id="total-harga">Rp0</span></p>
          <!-- Tombol Buat Pesanan -->
          <button id="buat-pesanan" class="mt-4 bg-green-500 text-white px-4 py-2 rounded w-full">Buat Pesanan</button>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
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
