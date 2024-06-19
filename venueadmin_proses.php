<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role_id'] != 1) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'add') {
        $nama_venue = $_POST['nama_venue'];
        $lokasi = $_POST['lokasi'];
        $kapasitas = $_POST['kapasitas'];

        $sql = "INSERT INTO venue (nama_venue, lokasi, kapasitas) VALUES (?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssi", $nama_venue, $lokasi, $kapasitas);
        $stmt->execute();
    } elseif ($action == 'update') {
        $id_venue = $_POST['id_venue'];
        $nama_venue = $_POST['nama_venue'];
        $lokasi = $_POST['lokasi'];
        $kapasitas = $_POST['kapasitas'];

        $sql = "UPDATE venue SET nama_venue=?, lokasi=?, kapasitas=? WHERE id_venue=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssii", $nama_venue, $lokasi, $kapasitas, $id_venue);
        $stmt->execute();
    } elseif ($action == 'delete') {
        $id_venue = $_POST['id_venue'];

        $sql = "DELETE FROM venue WHERE id_venue=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $id_venue);
        $stmt->execute();
    }

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Action successful.'); window.location.href='venueadmin.php';</script>";
    } else {
        echo "<script>alert('Action failed.'); window.history.back();</script>";
    }
    $stmt->close();
    $koneksi->close();
}
