<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'koneksi.php'; // Pastikan file koneksi benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = isset($_POST['role']) ? $_POST['role'] : 'petugas'; // Default role adalah petugas

    // Cek apakah username sudah ada
    $stmt = $conn->prepare("SELECT UserID FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Username sudah digunakan! Silakan pilih username lain.";
        header("Location: registrasi.php");
        exit;
    }
    $stmt->close();

    // Validasi konfirmasi password
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Konfirmasi password tidak cocok!";
        header("Location: registrasi.php");
        exit;
    }

    // Simpan password dalam bentuk teks biasa (Tidak direkomendasikan untuk produksi)
    $stmt = $conn->prepare("INSERT INTO user (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat registrasi.";
        header("Location: registrasi.php");
        exit;
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
    <title>Registrasi Akun</title>
    <link rel="stylesheet" href="registrasi.css"> 
</head>
<body>
    <h2>Registrasi Akun Baru</h2>
    
    <?php 
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    
    if (isset($_SESSION['success'])) {
        echo "<p style='color: green;'>" . $_SESSION['success'] . "</p>";
        unset($_SESSION['success']);
    }
    ?>

    <form action="registrasi.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="confirm_password">Konfirmasi Password:</label>
        <input type="password" name="confirm_password" required><br>

        <label for="role">Pilih Role:</label>
        <select name="role" required>
            <option value="petugas">Petugas</option>
            <option value="admin">Admin</option>
        </select><br>

        <button type="submit">Daftar</button>
    </form>
</body>
</html>
