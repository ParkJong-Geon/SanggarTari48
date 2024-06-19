<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role_id'] != 1) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $nama_souvenir = $_POST['nama_souvenir'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "souvenir/";
    $target_file = $target_dir . basename($gambar);
    move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file);

    if ($action == 'add') {
        $sql = "INSERT INTO souvenir (nama_souvenir, harga, stok, gambar) VALUES (?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssis", $nama_souvenir, $harga, $stok, $gambar);
        $stmt->execute();
    } elseif ($action == 'update') {
        $id_souvenir = $_POST['id_souvenir'];
        $sql = "UPDATE souvenir SET nama_souvenir=?, harga=?, stok=?, gambar=? WHERE id_souvenir=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssisi", $nama_souvenir, $harga, $stok, $gambar, $id_souvenir);
        $stmt->execute();
    } elseif ($action == 'delete') {
        $id_souvenir = $_POST['id_souvenir'];
        $sql = "DELETE FROM souvenir WHERE id_souvenir=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $id_souvenir);
        $stmt->execute();
    }

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Action successful.'); window.location.href='souveniradmin.php';</script>";
    } else {
        echo "<script>alert('Action failed.'); window.history.back();</script>";
    }
    $stmt->close();
    $koneksi->close();
}
?>
