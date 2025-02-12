<?php
session_start();
include 'koneksi.php'; // Pastikan file koneksi sudah benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pelanggan_id = trim($_POST['pelanggan_id']);
    $nama_pelanggan = trim($_POST['nama_pelanggan']);
    $alamat = trim($_POST['alamat']);
    $nomor_telepon = trim($_POST['nomor_telepon']);

    // Cek apakah PelangganID sudah ada
    $stmt = $conn->prepare("SELECT PelangganID FROM pelanggan WHERE PelangganID = ?");
    $stmt->bind_param("s", $pelanggan_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Pelanggan ID sudah digunakan! Silakan pilih ID lain.";
        header("Location: tambah_pelanggan.php");
        exit;
    }
    $stmt->close();

    // Masukkan data ke database
    $stmt = $conn->prepare("INSERT INTO pelanggan (PelangganID, NamaPelanggan, Alamat, NomorTelepon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $pelanggan_id, $nama_pelanggan, $alamat, $nomor_telepon);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Pelanggan berhasil ditambahkan!";
        header("Location: daftar_pelanggan.php"); // Redirect ke daftar pelanggan
        exit;
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat menambahkan pelanggan.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Tambah Pelanggan</h2>
        
        <?php 
        if (isset($_SESSION['error'])) {
            echo "<p class='text-danger'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        
        if (isset($_SESSION['success'])) {
            echo "<p class='text-success'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        }
        ?>

        <form action="tambah_pelanggan.php" method="POST">
            <div class="mb-3">
                <label for="pelanggan_id" class="form-label">Pelanggan ID:</label>
                <input type="text" name="pelanggan_id" id="pelanggan_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nama_pelanggan" class="form-label">Nama Pelanggan:</label>
                <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat:</label>
                <textarea name="alamat" id="alamat" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="nomor_telepon" class="form-label">Nomor Telepon:</label>
                <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Pelanggan</button>
        </form>

        <a href="dashboard.php" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
    </div>
</body>
</html>
