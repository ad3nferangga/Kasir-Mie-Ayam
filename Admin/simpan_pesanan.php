<?php
ob_start();
header('Content-Type: application/json');
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal      = $_POST['tanggal'];
    $nama_pemesan = $_POST['nama_pemesan'];
    $status       = $_POST['status'];
    $order        = $_POST['order'];
    $keranjang    = json_decode($_POST['keranjang'], true);

    if (empty($keranjang)) {
        echo json_encode(["status" => "error", "message" => "Keranjang kosong!"]);
        ob_end_clean();
        exit;
    }

    $menuItems   = [];
    $totalHarga  = 0;
    $totalQty    = 0;

    foreach ($keranjang as $item) {
        $menuItems[] = $item['nama'] . ' (x' . $item['qty'] . ')';
        $totalHarga += $item['harga'] * $item['qty'];
        $totalQty   += $item['qty'];
    }

    $menuString = implode(', ', $menuItems);

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

ob_end_clean();
mysqli_close($conn);
