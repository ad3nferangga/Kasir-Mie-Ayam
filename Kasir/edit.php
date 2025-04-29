<?php
include '../koneksi.php';


// Pastikan koneksi berhasil
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// --------------------------------------
// 1. Ambil data pesanan lama
// --------------------------------------
if (!isset($_GET['id'])) {
    echo "ID pesanan tidak disediakan";
    exit;
}

$idPesanan = $_GET['id'];

// Query data pesanan dari tabel `kasir`
$queryPesanan = "SELECT * FROM kasir WHERE id = '$idPesanan'";
$resultPesanan = mysqli_query($conn, $queryPesanan);

if (!$resultPesanan || mysqli_num_rows($resultPesanan) === 0) {
    echo "Data pesanan tidak ditemukan";
    exit;
}

$rowPesanan = mysqli_fetch_assoc($resultPesanan);

// Ambil field penting
$tanggalLama   = $rowPesanan['Tanggal'];         // 2025-03-19 (misalnya)
$namaPemesan   = $rowPesanan['nama_pemesan'];    // "AMRI GPT"
$menuString    = $rowPesanan['menu'];            // "Mie Ayam Biasa (x1), Bakso (x1), Es Teh Manis (x1)"
$jumlah        = $rowPesanan['jumlah'];          // 3 (jika Anda simpan total item)
$harga         = $rowPesanan['harga'];           // 45000 (jika Anda simpan total harga)

// --------------------------------------
// 2. Ambil daftar menu dari tabel `menu`
// --------------------------------------
$queryMie = "SELECT * FROM menu WHERE Katagori='mie-ayam' ORDER BY nama_produk ASC";
$resultMie = mysqli_query($conn, $queryMie);

$queryBakso = "SELECT * FROM menu WHERE Katagori='bakso' ORDER BY nama_produk ASC";
$resultBakso = mysqli_query($conn, $queryBakso);

$queryMinum = "SELECT * FROM menu WHERE Katagori='minuman' ORDER BY nama_produk ASC";
$resultMinum = mysqli_query($conn, $queryMinum);

// Cek jika query berhasil
if (!$resultMie || !$resultBakso || !$resultMinum) {
    die("Error query menu: " . mysqli_error($conn));
}

// --------------------------------------
// 3. Proses update data ke DB (AJAX)
// --------------------------------------
// Nanti di bagian JavaScript akan kirim ke edit.php (method POST) 
// dengan parameter “mode=updatePesanan”. 
// Kita tangani di sini agar 1 file saja.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode']) && $_POST['mode'] === 'updatePesanan') {
    // Ambil data dari fetch AJAX
    $tanggalBaru = $_POST['tanggal'];
    $namaBaru    = $_POST['nama_pemesan'];
    $keranjang   = json_decode($_POST['keranjang'], true);

    // Buat string menu baru (seperti di Kasir.php)
    $menuItems = [];
    $totalHargaBaru = 0;
    $totalQtyBaru   = 0;

    foreach ($keranjang as $item) {
        // contoh: "Mie Ayam Biasa (x2)"
        $menuItems[] = $item['nama'] . " (x" . $item['qty'] . ")";
        $totalHargaBaru += ($item['harga'] * $item['qty']);
        $totalQtyBaru   += $item['qty'];
    }
    $menuStringBaru = implode(", ", $menuItems);

    // Lakukan update ke tabel kasir
    $update = "UPDATE kasir SET
               Tanggal      = '$tanggalBaru',
               nama_pemesan = '$namaBaru',
               menu         = '$menuStringBaru',
               jumlah       = '$totalQtyBaru',
               harga        = '$totalHargaBaru'
               WHERE id = '$idPesanan'";

    $hasilUpdate = mysqli_query($conn, $update);

    if ($hasilUpdate) {
        // Beri response JSON
        echo json_encode([
            "status"  => "success",
            "message" => "Pesanan berhasil diupdate!"
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Gagal update: " . mysqli_error($conn)
        ]);
    }
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
    <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="Kasir.php">Home</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="Konfirmasi.php">pesanan</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="report.php">Report</a></li>
    </ul>
    <a class="flex items-center text-lg p-3 bg-red-600 hover:bg-red-700 rounded-lg mt-auto" href="../index.php">
      Logout
    </a>
  </div>

  <!-- MAIN CONTENT -->
  <div class="container mx-auto p-4 flex-1">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

      <!-- BAGIAN KIRI: Daftar Menu -->
      <div class="lg:col-span-2">
        <div class="bg-green-600 text-white p-4 rounded-t-lg flex items-center">
          <h2 class="text-lg font-semibold">Data Menu (Edit Mode)</h2>
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
                  <img src="../Admin/<?= htmlspecialchars($row['gambar']); ?>" class="w-full h-40 object-cover" alt="<?= htmlspecialchars($row['nama_produk']); ?>">
                  <div class="p-4">
                    <h3 class="text-green-600 font-semibold"><?= htmlspecialchars($row['nama_produk']); ?></h3>
                    <p class="text-green-600">Rp<?= number_format($row['price'], 0, ',', '.'); ?>,-</p>
                    <div class="flex space-x-2 mt-2">
                      <button class="bg-blue-500 text-white px-4 py-2 rounded w-full tambah-keranjang">
                        Tambah ke Keranjang
                      </button>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>

          <?php 
          // Reset pointer result set agar bisa dipakai lagi
          // (Hanya perlu jika $resultMie, $resultBakso, $resultMinum dipakai ulang, 
          //  tapi di sini kita sudah habis looping, jadi tidak perlu di-reset.)
          ?>

          <!-- Bakso Section -->
          <div id="bakso-section" class="border border-gray-700 rounded-lg p-4 mb-6 menu-section bakso">
            <div class="text-center mb-4">
              <div class="border border-gray-500 rounded-full px-6 py-2 inline-block text-gray-700 font-semibold bg-gray-200">
                Bakso
              </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
              <?php 
              // Jalankan ulang query bakso (karena di atas sudah di-fetch)
              mysqli_data_seek($resultBakso, 0);
              while($row = mysqli_fetch_assoc($resultBakso)) { ?>
                <div class="border border-gray-500 rounded-lg shadow-md overflow-hidden menu-item"
                     data-id="<?= $row['id']; ?>"
                     data-nama="<?= htmlspecialchars($row['nama_produk']); ?>"
                     data-harga="<?= $row['price']; ?>">
                  <img src="../Admin/<?= htmlspecialchars($row['gambar']); ?>" class="w-full h-40 object-cover" alt="<?= htmlspecialchars($row['nama_produk']); ?>">
                  <div class="p-4">
                    <h3 class="text-green-600 font-semibold"><?= htmlspecialchars($row['nama_produk']); ?></h3>
                    <p class="text-green-600">Rp<?= number_format($row['price'], 0, ',', '.'); ?>,-</p>
                    <div class="flex space-x-2 mt-2">
                      <button class="bg-blue-500 text-white px-4 py-2 rounded w-full tambah-keranjang">
                        Tambah ke Keranjang
                      </button>
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
              <?php 
              // Jalankan ulang query minuman
              mysqli_data_seek($resultMinum, 0);
              while($row = mysqli_fetch_assoc($resultMinum)) { ?>
                <div class="border border-gray-500 rounded-lg shadow-md overflow-hidden menu-item"
                     data-id="<?= $row['id']; ?>"
                     data-nama="<?= htmlspecialchars($row['nama_produk']); ?>"
                     data-harga="<?= $row['price']; ?>">
                  <img src="../Admin/<?= htmlspecialchars($row['gambar']); ?>" class="w-full h-40 object-cover" alt="<?= htmlspecialchars($row['nama_produk']); ?>">
                  <div class="p-4">
                    <h3 class="text-green-600 font-semibold"><?= htmlspecialchars($row['nama_produk']); ?></h3>
                    <p class="text-green-600">Rp<?= number_format($row['price'], 0, ',', '.'); ?>,-</p>
                    <div class="flex space-x-2 mt-2">
                      <button class="bg-blue-500 text-white px-4 py-2 rounded w-full tambah-keranjang">
                        Tambah ke Keranjang
                      </button>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>

        </div>
      </div>

      <!-- BAGIAN KANAN: Keranjang + Form -->
      <div>
        <div class="bg-green-600 text-white p-4 rounded-t-lg">
          <h2 class="text-lg font-semibold">Edit Keranjang</h2>
        </div>
        <div class="bg-white p-4 rounded-b-lg shadow-md">
          <!-- Input Tanggal -->
          <label class="block text-gray-700">Tanggal</label>
          <input type="date" id="tanggal-pesanan" class="border border-gray-300 rounded px-4 py-2 w-full mb-4"
                 value="<?php echo date('Y-m-d', strtotime($tanggalLama)); ?>" />

          <!-- Input Nama Pemesan -->
          <label class="block text-gray-700">ATAS NAMA</label>
          <input type="text" id="atas-nama" class="border border-gray-300 rounded px-4 py-2 w-full mb-4"
                 value="<?php echo htmlspecialchars($namaPemesan); ?>" />

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

          <!-- Tombol Update Pesanan -->
          <button id="update-pesanan" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded w-full">
            Update Pesanan
          </button>
        </div>
      </div>

    </div>
  </div>

   <!-- SCRIPT -->
   <script>
    // ------------------------------
    // 1. Filter kategori (opsional)
    // ------------------------------
    document.getElementById("filter-kategori").addEventListener("change", function() {
      let kategori = this.value;
      document.querySelectorAll(".menu-section").forEach(function(section) {
        section.style.display = (kategori === "all" || section.classList.contains(kategori))
                                ? "block" : "none";
      });
    });

    // ------------------------------
    // 2. Keranjang Logic
    // ------------------------------
    let keranjang = [];

    // Fungsi render keranjang (plus/minus dan tombol X)
    function renderKeranjang() {
      const tbody = document.getElementById("keranjang-list");
      tbody.innerHTML = "";

      let totalHarga = 0;

      keranjang.forEach((item, index) => {
        const subTotal = item.harga * item.qty;
        totalHarga += subTotal;

        const tr = document.createElement("tr");
        tr.innerHTML = `
  <td class="border px-4 py-2 text-center">${index + 1}</td>
  <td class="border px-4 py-2">${item.nama}</td>
  <td class="border px-4 py-2">
    <div class="flex items-center justify-center space-x-2">
      <button
        class="decrease w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
        data-index="${index}">−
      </button>
      <span class="font-medium">${item.qty}</span>
      <button
        class="increase w-8 h-8 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded"
        data-index="${index}">+
      </button>
    </div>
  </td>
  <td class="border px-4 py-2 text-right">Rp${subTotal.toLocaleString('id-ID')}</td>
  <td class="border px-4 py-2 text-center">
    <button
      class="hapus-item w-8 h-8 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded"
      data-index="${index}">X
    </button>
  </td>
`;

        tbody.appendChild(tr);
      });

      // Update Total Bayar
      document.getElementById("total-harga").textContent =
        "Rp" + totalHarga.toLocaleString('id-ID');

      // Event listener Minus
      document.querySelectorAll(".decrease").forEach(btn => {
        btn.addEventListener("click", () => {
          const i = btn.getAttribute("data-index");
          if (keranjang[i].qty > 1) {
            keranjang[i].qty--;
            renderKeranjang();
          }
        });
      });

      // Event listener Plus
      document.querySelectorAll(".increase").forEach(btn => {
        btn.addEventListener("click", () => {
          const i = btn.getAttribute("data-index");
          keranjang[i].qty++;
          renderKeranjang();
        });
      });

      // Event listener Hapus (X)
      document.querySelectorAll(".hapus-item").forEach(btn => {
        btn.addEventListener("click", () => {
          const i = btn.getAttribute("data-index");
          keranjang.splice(i, 1);
          renderKeranjang();
        });
      });
    }

    // ------------------------------
    // 3. Tambah item ke keranjang
    // ------------------------------
    document.querySelectorAll(".tambah-keranjang").forEach(button => {
      button.addEventListener("click", () => {
        const menu = button.closest(".menu-item");
        const nama = menu.getAttribute("data-nama");
        const harga = parseInt(menu.getAttribute("data-harga"));

        const existing = keranjang.find(i => i.nama === nama);
        if (existing) existing.qty++;
        else keranjang.push({ nama, harga, qty: 1 });

        renderKeranjang();
      });
    });

    // ------------------------------
    // 4. Isi keranjang awal dari DB
    // ------------------------------
    const menuStringLama = <?php echo json_encode($menuString); ?>;
    function parseMenuString(str) {
      return str.split(", ").map(it => {
        const nama = it.slice(0, it.lastIndexOf(" (x"));
        const qty = parseInt(it.slice(it.lastIndexOf(" (x")+3, -1));
        return { nama, qty };
      });
    }
    parseMenuString(menuStringLama).forEach(old => {
      document.querySelectorAll(".menu-item").forEach(menu => {
        if (menu.getAttribute("data-nama") === old.nama) {
          keranjang.push({
            nama: old.nama,
            harga: parseInt(menu.getAttribute("data-harga")),
            qty: old.qty
          });
        }
      });
    });
    renderKeranjang();

    // ------------------------------
    // 5. Kirim update ke server
    // ------------------------------
    document.getElementById("update-pesanan").addEventListener("click", () => {
      const tanggal = document.getElementById("tanggal-pesanan").value;
      const namaPemesan = document.getElementById("atas-nama").value;
      if (keranjang.length === 0) return alert("Keranjang kosong!");

      fetch(window.location.href, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
          mode: "updatePesanan",
          tanggal,
          nama_pemesan: namaPemesan,
          keranjang: JSON.stringify(keranjang)
        })
      })
      .then(r => r.json())
      .then(data => {
        if (data.status === "success") {
          alert(data.message);
          window.location.href = "Konfirmasi.php";
        } else alert("Gagal update: " + data.message);
      })
      .catch(() => alert("Terjadi kesalahan."));
    });
  </script>


</body>
</html>
