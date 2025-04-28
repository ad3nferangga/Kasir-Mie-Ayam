<?php
include '../koneksi.php'; // Menghubungkan ke database

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    // Pastikan name="katagori" di form HTML sesuai dengan $_POST['katagori']
    // atau ganti di sini menjadi $_POST['kategori'] jika form menggunakan name="kategori"
    $katagori = $_POST['katagori']; // Ambil nilai kategori

    // Validasi data
    if (empty($name) || empty($price) || empty($katagori) || !isset($_FILES['image'])) {
        echo json_encode(["success" => false, "message" => "All fields are required!"]);
        exit;
    }

    // Upload file
    $imageName = basename($_FILES["image"]["name"]);
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $imagePath = $targetDir . time() . "_" . $imageName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
        // Simpan data ke database
        // Sesuaikan dengan kolom Anda: nama_produk, price, gambar, Katagori
        $stmt = $conn->prepare("INSERT INTO menu (nama_produk, price, gambar, Katagori) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $name, $price, $imagePath, $katagori);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "image" => $imagePath]);
        } else {
            echo json_encode(["success" => false, "message" => "Database insertion failed!"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Image upload failed!"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request!"]);
}

$conn->close();
?>
