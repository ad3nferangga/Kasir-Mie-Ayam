<?php
header('Content-Type: application/json; charset=utf-8');
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status"=>"error","message"=>"Metode tidak diizinkan."]);
    exit;
}

$tanggal      = $_POST['tanggal']      ?? '';
$nama_pemesan = $_POST['nama_pemesan'] ?? '';
$metode       = $_POST['metode']       ?? '';
$order        = $_POST['order']        ?? '';
$keranjang    = json_decode($_POST['keranjang'] ?? '[]', true);

if (empty($keranjang)) {
    echo json_encode(["status"=>"error","message"=>"Keranjang kosong!"]);
    exit;
}

// siapkan data
$totalHarga = 0; $totalQty = 0; $menuItems = [];
foreach ($keranjang as $item) {
    $menuItems[] = $item['nama'] . ' (x'.$item['qty'].')';
    $totalHarga += $item['harga'] * $item['qty'];
    $totalQty   += $item['qty'];
}
$menuString = implode(', ', $menuItems);

// insert
$sql = "INSERT INTO kasir
    (Tanggal,nama_pemesan,menu,harga,jumlah,status,`order`)
  VALUES
    ('$tanggal','$nama_pemesan','$menuString',$totalHarga,$totalQty,'$metode','$order')";

if (mysqli_query($conn,$sql)) {
    echo json_encode(["status"=>"success","message"=>"Pesanan berhasil disimpan!"]);
} else {
    http_response_code(500);
    echo json_encode(["status"=>"error","message"=>"Gagal menyimpan pesanan."]);
}
mysqli_close($conn);
