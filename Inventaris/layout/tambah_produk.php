<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = trim($_POST['nama_produk']);
    $harga = (int) $_POST['harga'];
    $stok = (int) $_POST['stok'];

    $sql = "INSERT INTO produk (NamaProduk, Harga, Stok) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $nama_produk, $harga, $stok);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Produk berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan produk.";
    }

    $stmt->close();
    $conn->close();
    header("Location: dashboard.php");
    exit;
}
?>
