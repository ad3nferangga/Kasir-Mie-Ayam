<?php
include '../koneksi.php'; // Pastikan koneksi database benar

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Siapkan query hapus
    $stmt = $conn->prepare("DELETE FROM menu WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus produk."]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Permintaan tidak valid."]);
}

$conn->close();
?>
