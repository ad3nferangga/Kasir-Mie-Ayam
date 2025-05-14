<?php
include '../koneksi.php';

$startDate = $_GET['start_date'] ?? '';
$endDate   = $_GET['end_date'] ?? '';

$query = "SELECT * FROM kasir";
if ($startDate && $endDate) {
    $query .= " WHERE Tanggal BETWEEN '$startDate' AND '$endDate'";
}
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

$menuList = [];
$resMenu = mysqli_query($conn, "SELECT nama_produk FROM menu ORDER BY nama_produk") or die(mysqli_error($conn));
while ($row = mysqli_fetch_assoc($resMenu)) {
    $menuList[] = $row['nama_produk'];
}

$salesMap = [];
foreach ($menuList as $m) {
    $sql = "SELECT COUNT(*) AS cnt FROM kasir WHERE menu LIKE '%" . mysqli_real_escape_string($conn, $m) . "%'";
    if ($startDate && $endDate) {
        $sql .= " AND Tanggal BETWEEN '$startDate' AND '$endDate'";
    }
    $rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $cnt = mysqli_fetch_assoc($rs)['cnt'];
    $salesMap[$m] = (int)$cnt;
}

$menus   = [];
$jumlahs = [];
foreach ($menuList as $m) {
    $menus[]   = $m;
    $jumlahs[] = $salesMap[$m] ?? 0;
}

$dateList    = [];
$salesByDate = [];
if ($startDate && $endDate) {
    $qDates = mysqli_query($conn,
        "SELECT DISTINCT Tanggal FROM kasir
         WHERE Tanggal BETWEEN '$startDate' AND '$endDate'
         ORDER BY Tanggal"
    ) or die(mysqli_error($conn));
    while ($d = mysqli_fetch_assoc($qDates)) {
        $dateList[] = $d['Tanggal'];
    }
    foreach ($dateList as $dt) {
        $r  = mysqli_query($conn, "SELECT SUM(jumlah) AS tot FROM kasir WHERE Tanggal = '$dt'") or die(mysqli_error($conn));
        $d2 = mysqli_fetch_assoc($r);
        $salesByDate[] = (int) $d2['tot'];
    }
}

$menusJson      = json_encode($menus);
$jumlahsJson    = json_encode($jumlahs);
$datesJson      = json_encode($dateList);
$salesDateJson  = json_encode($salesByDate);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Report Penjualan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    @media print {
      .no-print { display: none !important; }
      .print-full { width: 100% !important; margin: 0; padding: 0; }
      thead { display: table-header-group; }
      tr { page-break-inside: avoid; }
    }
    body { font-family: sans-serif; }
    table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    th, td { border: 1px solid #ccc; padding: .5rem; }
    th { background: #f3f4f6; }
    #chart-container {
      width: 100%;
      max-width: 1200px; /* Lebih besar */
      margin: auto;
    }
  </style>
</head>
<body class="flex bg-orange-50 min-h-screen">
  <div class="w-64 bg-teal-800 text-white p-5 flex flex-col no-print">
    <h2 class="text-2xl font-bold text-center mb-4">MIE AYAM</h2>
    <ul class="flex-1 space-y-3">
    <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="admin_dashboard.php">Home</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="Konfirmasi.php">pesanan</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="report.php">Report</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="akun.php">Tambah Akun</a></li>
      <li><a class="flex items-center text-lg p-3 hover:bg-teal-600 rounded-lg" href="add_product.php">Add Product</a></li>
        <li><a href="about.php" class="block p-3 rounded hover:bg-teal-600">About</a></li>
    </ul>
    <a href="../index.php" class="block p-2 bg-red-600 hover:bg-red-700 rounded mt-auto">Logout</a>
  </div>

  <main class="flex-1 p-6 print-full">
    <h2 class="text-xl font-bold mb-4">Report Penjualan</h2>

    <form method="GET" class="mb-6 no-print flex space-x-2">
      <input type="date" name="start_date" value="<?=htmlspecialchars($startDate)?>" class="border p-2"/>
      <input type="date" name="end_date" value="<?=htmlspecialchars($endDate)?>" class="border p-2"/>
      <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded">Generate</button>
      <button type="button" onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">Print</button>
    </form>

    <table>
      <thead><tr><th>No</th><th>Tanggal</th><th>Pemesan</th><th>Menu</th><th>Qty</th><th>Harga</th></tr></thead>
      <tbody>
        <?php if(mysqli_num_rows($result)>0): $no=1; while($r=mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?=$no++?></td>
            <td><?=date('d/m/Y',strtotime($r['Tanggal']))?></td>
            <td><?=htmlspecialchars($r['nama_pemesan'])?></td>
            <td><?=htmlspecialchars($r['menu'])?></td>
            <td><?=$r['jumlah']?></td>
            <td>Rp<?=number_format($r['harga'],0,',','.')?></td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="6" class="text-center">No Data</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="mt-8 no-print">
      <h2 class="text-center font-bold mb-4">Grafik Penjualan</h2>

      <div class="flex space-x-2 mb-4">
        <button type="button" onclick="changeChart('bar')" class="bg-teal-700 text-white px-4 py-2 rounded">Bar Chart</button>
        <button type="button" onclick="changeChart('line')" class="bg-blue-600 text-white px-4 py-2 rounded">Line Chart</button>
        <button type="button" onclick="changeChart('mix')" class="bg-green-600 text-white px-4 py-2 rounded">Gabungan</button>
      </div>

      <div id="chart-container" class="w-full flex justify-center no-print">
        <canvas id="barChart" width="600" height="300" class="mb-6"></canvas>
      </div>
    </div>
  </main>

  <script>
    const mLabels = <?=$menusJson?>;
    const mData   = <?=$jumlahsJson?>;
    const dLabels = <?=$datesJson?>;
    const dData   = <?=$salesDateJson?>;

    let currentChart = null;

    function renderChart(type) {
      if (currentChart) {
        currentChart.destroy();
      }
      let ctx = document.getElementById('barChart').getContext('2d');
      
      if (type === 'mix') {
        currentChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: mLabels,
            datasets: [
              {
                label: 'Order Count',
                data: mData,
                backgroundColor: 'rgba(59,130,246,0.7)',
                borderColor: 'rgba(37,99,235,1)',
                borderWidth: 1
              },
              {
                label: 'Total Qty (Line)',
                type: 'line',
                data: mData,
                borderColor: 'rgba(16,185,129,1)',
                backgroundColor: 'transparent',
                tension: 0.4
              }
            ]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
                max: 50
              }
            }
          }
        });
      } else {
        currentChart = new Chart(ctx, {
          type: type,
          data: {
            labels: mLabels,
            datasets: [{
              label: type === 'bar' ? 'Order Count' : 'Total Qty',
              data: mData,
              backgroundColor: type === 'bar' ? 'rgba(59,130,246,0.7)' : 'transparent',
              borderColor: type === 'bar' ? 'rgba(37,99,235,1)' : 'rgba(16,185,129,1)',
              borderWidth: 2,
              fill: type === 'line'
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
                max: 50
              }
            }
          }
        });
      }
    }

    function changeChart(type) {
      renderChart(type);
    }

    renderChart('bar');
  </script>
</body>
</html>
