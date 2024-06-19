<?php
include "koneksi.php";
session_start();

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['id_user']) || $_SESSION['role_id'] != 1) {
    header('Location: login.php');
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$nama_event = $_POST['nama_event'];
$tanggal_event = $_POST['tanggal_event'];
$venue = $_POST['venue'];  // Pastikan input untuk venue benar

$action = $_POST['action'];
$id_event = $_POST['id_event'];

if ($action == 'add') {
    // Menyimpan data event
    $sqlInsert = "INSERT INTO event (nama_event, tanggal_event, id_venue) VALUES (?, ?, ?)";
    $stmtInsert = $koneksi->prepare($sqlInsert);
    $stmtInsert->bind_param("ssi", $nama_event, $tanggal_event, $venue);

    if ($stmtInsert->execute()) {
        $id_event = $stmtInsert->insert_id; // Mengambil ID dari event yang baru ditambahkan

        // Menambahkan entri di tabel tiket dengan harga awal 0 atau default
        $sqlTiket = "INSERT INTO tiket (id_event, harga, stok) VALUES (?, 0, 0)";
        $stmtTiket = $koneksi->prepare($sqlTiket);
        $stmtTiket->bind_param("i", $id_event);

        if ($stmtTiket->execute()) {
            echo "<script>alert('Event dan tiket default berhasil ditambahkan.'); window.location.href='eventadmin.php';</script>";
        } else {
            echo "Error saat menambahkan tiket: " . $stmtTiket->error;
        }

        $stmtTiket->close();
    } else {
        echo "Error saat menambahkan event: " . $stmtInsert->error;
    }

    $stmtInsert->close();
} elseif ($action == 'update') {
    // Update data event
    $sqlUpdate = "UPDATE event SET nama_event=?, tanggal_event=?, id_venue=? WHERE id_event=?";
    $stmtUpdate = $koneksi->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssii", $nama_event, $tanggal_event, $venue, $id_event);

    if ($stmtUpdate->execute()) {
        echo "<script>alert('Event berhasil diupdate.'); window.location.href='eventadmin.php';</script>";
    } else {
        echo "Error saat mengupdate event: " . $stmtUpdate->error;
    }

    $stmtUpdate->close();
} elseif ($action == 'delete') {
    // Delete data event dan tiket yang terkait
    $sqlDeleteTiket = "DELETE FROM tiket WHERE id_event=?";
    $stmtDeleteTiket = $koneksi->prepare($sqlDeleteTiket);
    $stmtDeleteTiket->bind_param("i", $id_event);
    $stmtDeleteTiket->execute();
    $stmtDeleteTiket->close();

    $sqlDeleteEvent = "DELETE FROM event WHERE id_event=?";
    $stmtDeleteEvent = $koneksi->prepare($sqlDeleteEvent);
    $stmtDeleteEvent->bind_param("i", $id_event);

    if ($stmtDeleteEvent->execute()) {
        echo "<script>alert('Event dan tiket berhasil dihapus.'); window.location.href='eventadmin.php';</script>";
    } else {
        echo "Error saat menghapus event: " . $stmtDeleteEvent->error;
    }

    $stmtDeleteEvent->close();
}

$koneksi->close();
?>
