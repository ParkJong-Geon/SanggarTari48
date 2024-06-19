<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $id_user = $_SESSION['id_user'];
    $point = $amount * 10; // Misal, Rp 1.000 = 10 point.

    $koneksi->begin_transaction();
    try {
        // Simpan request pembelian poin ke dalam tabel order
        $sqlOrder = "INSERT INTO `order` (id_user, tanggal_order, total_harga, status, jenis_order) VALUES (?, NOW(), ?, 'pending', 'point')";
        $stmtOrder = $koneksi->prepare($sqlOrder);
        $stmtOrder->bind_param("ii", $id_user, $amount);
        $stmtOrder->execute();
        $id_order = $stmtOrder->insert_id;

        $koneksi->commit();
        echo "<script>alert('Permintaan pembelian poin Anda telah diajukan. Menunggu konfirmasi admin.'); window.location.href='user_home.php';</script>";
    } catch (Exception $e) {
        $koneksi->rollback();
        echo "Error: " . $koneksi->error;
    }

    $stmtOrder->close();
    $koneksi->close();
}
?>
