<?php
session_start();
include 'koneksi.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['id_user']) || $_SESSION['role_id'] != 1) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_event = $_POST['id_event'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Cek apakah harga tiket untuk event tersebut sudah ada
    $sql = "SELECT harga, stok FROM tiket WHERE id_event = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_event);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update harga jika sudah ada
        $sql = "UPDATE tiket SET harga = ?, stok = ? WHERE id_event = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sii", $harga, $stok, $id_event);
    } else {
        // Insert harga baru jika belum ada
        $sql = "INSERT INTO tiket (id_event, harga, stok) VALUES (?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sii", $id_event, $harga, $stok);
    }

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Tiket berhasil diupdate.'); window.location.href='tiketadmin.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate tiket.'); window.history.back();</script>";
    }

    $stmt->close();
    $koneksi->close();
}
?>
