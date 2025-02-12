<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Menggunakan koneksi dari koneksi.php
include 'koneksi.php';

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_barang = trim($_POST['nama_barang']);
    $harga       = intval($_POST['harga']);
    $stok        = intval($_POST['stok']);
    $suplier_ID  = intval($_POST['suplier_ID']); // Ambil ID supplier dari form

    // Simpan data ke tabel barang (tanpa kolom tanggal karena tidak ada di struktur tabel barang)
    $sql_barang = "INSERT INTO barang (nama_barang, harga, stok) VALUES (?, ?, ?)";
    $stmt_barang = $conn->prepare($sql_barang);
    $stmt_barang->bind_param("sii", $nama_barang, $harga, $stok);

    if ($stmt_barang->execute()) {
        $id_barang   = $stmt_barang->insert_id; // Ambil ID barang yang baru dimasukkan
        $total_harga = $harga * $stok;
        // Karena kolom total_harga di tabel pembelian bertipe varchar(50), 
        // konversikan total_harga menjadi string
        $total_harga_str = strval($total_harga);

        /* 
           Masukkan data ke tabel pembelian.
           Kolom yang diisi:
           - id_barang: dari insert barang
           - suplier_ID: dari form
           - user_id: diambil dari session
           - total_harga: hasil perhitungan (sebagai string)
           Kolom tanggal_pembelian tidak dimasukkan agar database mengisinya otomatis
           dengan current_timestamp().
        */
        $sql_pembelian = "INSERT INTO pembelian (id_barang, suplier_ID, user_id, total_harga) VALUES (?, ?, ?, ?)";
        $stmt_pembelian = $conn->prepare($sql_pembelian);
        $stmt_pembelian->bind_param("iiis", $id_barang, $suplier_ID, $user_id, $total_harga_str);

        if ($stmt_pembelian->execute()) {
            echo "<script>
                    alert('Barang dan pembelian berhasil ditambahkan!');
                    window.location='dashboard.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menambahkan pembelian: " . $stmt_pembelian->error . "');
                    window.location='dashboard.php';
                  </script>";
        }
        $stmt_pembelian->close();
    } else {
        echo "<script>
                alert('Gagal menambahkan barang: " . $stmt_barang->error . "');
                window.location='dashboard.php';
              </script>";
    }
    $stmt_barang->close();
}

$conn->close();
?>
