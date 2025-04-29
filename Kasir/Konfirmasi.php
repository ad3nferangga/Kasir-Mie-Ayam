<?php
include '../koneksi.php';

// Ambil data dari database
$query  = "SELECT * FROM kasir";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pesanan Mie Ayam</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Print‐styles untuk struk window */
    @media print {
      .btn-print { display: none !important; }
      body { margin: 0; padding: 0; }
    }

    /* Styling umum struk */
    .struk-body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      color: #333;
    }
    .struk-body h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.5rem;
    }
    .struk-body .info p {
      margin: 4px 0;
      font-size: 0.95rem;
    }
    .struk-body .info strong {
      display: inline-block;
      width: 80px;
    }
    .struk-body table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    .struk-body th,
    .struk-body td {
      border: 1px solid #444;
      padding: 8px;
      font-size: 0.9rem;
      text-align: left;
    }
    .struk-body th {
      background: #f0f0f0;
    }
    .struk-body .total {
      text-align: right;
      margin-top: 15px;
      font-size: 1.1rem;
      font-weight: bold;
    }
    .struk-body .btn-print {
      display: block;
      width: 100%;
      margin-top: 30px;
      padding: 10px;
      background: #007bff;
      color: #fff;
      text-align: center;
      text-decoration: none;
      border-radius: 4px;
    }
  </style>
</head>
<body class="bg-orange-100 flex min-h-screen">

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
    <a class="flex items-center text-lg p-3 bg-red-600 hover:bg-red-700 rounded-lg mt-auto" href="../index.php">Logout</a>
  </div>

  <!-- Main Content -->
  <main class="flex-1 p-4 print-fullwidth">
    <div class="container mx-auto p-4 print-fullwidth">
      <div class="bg-white p-6 rounded-lg shadow-lg print-fullwidth">
        <h2 class="text-2xl font-semibold mb-4">Pesanan</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-300">
            <thead>
              <tr class="bg-gray-200">
                <th class="py-2 px-4 border-b">No</th>
                <th class="py-2 px-4 border-b">Date</th>
                <th class="py-2 px-4 border-b">Nama</th>
                <th class="py-2 px-4 border-b">Item</th>
                <th class="py-2 px-4 border-b">Quantity</th>
                <th class="py-2 px-4 border-b">Total Price</th>
                <th class="py-2 px-4 border-b no-print">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result && mysqli_num_rows($result) > 0) {
                  $no = 1;
                  while ($row = mysqli_fetch_assoc($result)) {
                      $totalPrice   = $row['harga'];
                      $formattedDate = date('d/m/Y', strtotime($row['Tanggal']));
                      $id            = $row['id'];
                      echo "<tr class='text-center'>";
                      echo "  <td class='py-2 px-4 border-b'>{$no}</td>";
                      echo "  <td class='py-2 px-4 border-b'>{$formattedDate}</td>";
                      echo "  <td class='py-2 px-4 border-b'>{$row['nama_pemesan']}</td>";
                      echo "  <td class='py-2 px-4 border-b'>{$row['menu']}</td>";
                      echo "  <td class='py-2 px-4 border-b'>{$row['jumlah']}</td>";
                      echo "  <td class='py-2 px-4 border-b'>Rp" . number_format($totalPrice,0,',','.') . "</td>";
                      echo "  <td class='py-2 px-4 border-b flex justify-center gap-2 no-print'>";
                      echo "    <a href='edit.php?id=$id'   class='bg-yellow-500 text-white px-3 py-1 rounded'>Edit</a>";
                      echo "    <a href='delete.php?id=$id' class='bg-red-500 text-white px-3 py-1 rounded'>Delete</a>";
                      echo "    <button ";
                      echo "      class='print-btn bg-green-500 text-white px-3 py-1 rounded' ";
                      echo "      data-id='{$id}' ";
                      echo "      data-nama='{$row['nama_pemesan']}' ";
                      echo "      data-tanggal='{$formattedDate}' ";
                      echo "      data-menu='{$row['menu']}' ";
                      echo "      data-jumlah='{$row['jumlah']}' ";
                      echo "      data-harga='{$totalPrice}'>";
                      echo "      Print";
                      echo "    </button>";
                      echo "  </td>";
                      echo "</tr>";
                      $no++;
                  }
              } else {
                  echo "<tr><td colspan='7' class='py-2 px-4 border-b text-center'>No data found</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Event listener untuk tombol Print
    document.querySelectorAll('.print-btn').forEach(function(button) {
      button.addEventListener('click', function() {
        const nama     = this.dataset.nama;
        const tanggal  = this.dataset.tanggal;
        const menu     = this.dataset.menu;
        const jumlah   = this.dataset.jumlah;
        const hargaRaw = parseInt(this.dataset.harga, 10);
        const harga    = hargaRaw.toLocaleString('id-ID');

        // Buka window struk
        let w = window.open('', 'Struk', 'width=600,height=800');
        w.document.write(`
          <!DOCTYPE html>
          <html lang="id">
          <head>
            <meta charset="UTF-8">
            <title>Struk Pembayaran</title>
            <style>
              /* Print‐styles */
              @media print { .btn-print { display: none !important; } }
              /* Styling struk */
              body        { margin:0; padding:20px; font-family:Arial,sans-serif; color:#333; }
              h2          { text-align:center; margin-bottom:20px; font-size:1.5rem; }
              .info p     { margin:4px 0; font-size:0.95rem; }
              .info strong { display:inline-block; width:80px; }
              table       { width:100%; border-collapse:collapse; margin-top:15px; }
              th,td       { border:1px solid #444; padding:8px; font-size:0.9rem; text-align:left; }
              th          { background:#f0f0f0; }
              .total      { text-align:right; margin-top:15px; font-size:1.1rem; font-weight:bold; }
              .btn-print  { display:block; width:100%; margin-top:30px; padding:10px; background:#007bff; color:#fff; text-align:center; text-decoration:none; border-radius:4px; }
            </style>
          </head>
          <body class="struk-body">
            <h2>Struk Pembayaran</h2>
            <div class="info">
              <p><strong>Nama:</strong> ${nama}</p>
              <p><strong>Tanggal:</strong> ${tanggal}</p>
            </div>
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Produk</th>
                  <th>Qty</th>
                  <th>Harga (Rp)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>${menu}</td>
                  <td>${jumlah}</td>
                  <td style="text-align:right;">${harga}</td>
                </tr>
              </tbody>
            </table>
            <div class="total">Total Bayar: Rp${harga}</div>
            <a href="javascript:window.print()" class="btn-print">Cetak Struk</a>
          </body>
          </html>
        `);
        w.document.close();
      });
    });
  </script>

</body>
</html>
