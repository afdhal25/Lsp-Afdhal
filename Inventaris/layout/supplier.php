<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NamaPelanggan = $_POST['NamaPelanggan'];
    $Alamat = $_POST['Alamat'];
    $NoTelepon = $_POST['NoTelepon'];

    $sql = "INSERT INTO pelanggan (NamaPelanggan, Alamat, NoTelepon) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $NamaPelanggan, $Alamat, $NoTelepon);
    if ($stmt->execute()) {
        echo "<script>alert('Pelanggan berhasil ditambahkan!'); window.location.href='tambah_pelanggan.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan pelanggan.');</script>";
    }
    $stmt->close();
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

<div class="container mt-4">
    <h2>Tambah Pelanggan</h2>
    <form action="tambah_pelanggan.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Pelanggan:</label>
            <input type="text" name="NamaPelanggan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat:</label>
            <input type="text" name="Alamat" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">No Telepon:</label>
            <input type="text" name="NoTelepon" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
    </form>
</div>

</body>
</html>
