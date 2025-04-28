<?php
include '../koneksi.php'; // Menghubungkan ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form, termasuk tanggal
    $tanggal      = $_POST['tanggal'];
    $nama_pemesan = $_POST['nama_pemesan'];
    $status       = $_POST['status'];
    $order        = $_POST['order'];
    $keranjang    = json_decode($_POST['keranjang'], true);

    if (empty($keranjang)) {
        echo json_encode(["status" => "error", "message" => "Keranjang kosong!"]);
        exit;
    }

    // Siapkan variabel untuk menampung gabungan item
    $menuItems   = [];   // Akan menampung nama + qty (mis. "Mie Ayam (x2), Bakso (x1)")
    $totalHarga  = 0;    // Total harga semua item
    $totalQty    = 0;    // Total qty semua item

    // Loop setiap item di keranjang
    foreach ($keranjang as $item) {
        // Contoh: "Mie Ayam (x2)"
        $menuItems[] = $item['nama'] . ' (x' . $item['qty'] . ')';
        
        // Tambahkan ke total harga
        $totalHarga += $item['harga'] * $item['qty'];
        
        // Tambahkan ke total qty
        $totalQty   += $item['qty'];
    }

    // Gabungkan semua item menjadi satu string
    // Misalnya: "Mie Ayam (x2), Bakso (x1), Es Teh Manis (x1)"
    $menuString = implode(', ', $menuItems);

    // Lakukan INSERT sekali saja
    // Pastikan nama kolom Tanggal sesuai dengan di database (huruf T besar atau kecil)
    $sql = "INSERT INTO kasir (Tanggal, nama_pemesan, menu, harga, jumlah, status, `order`)
            VALUES ('$tanggal', '$nama_pemesan', '$menuString', '$totalHarga', '$totalQty', '$status', '$order')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Pesanan berhasil disimpan!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan pesanan."]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan."]);
}

mysqli_close($conn);
