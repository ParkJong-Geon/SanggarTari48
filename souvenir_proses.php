<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_SESSION['id_user'];
    $id_souvenir = $_POST['id_souvenir'];
    $jumlah = $_POST['jumlah'];

    // Cek stok dan harga souvenir
    $sql = "SELECT harga, stok FROM souvenir WHERE id_souvenir = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_souvenir);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $harga_souvenir = $row['harga'];
    $stok_souvenir = $row['stok'];
    $stmt->close();

    if ($jumlah > $stok_souvenir) {
        echo "<script>alert('Stok tidak cukup.'); window.history.back();</script>";
        exit;
    }

    $total_harga = $harga_souvenir * $jumlah;

    // Cek jika user memiliki cukup poin
    $sql = "SELECT total_point FROM profil WHERE id_user = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $total_point = $data['total_point'];
    $stmt->close();

    if ($total_point >= $total_harga) {
        $koneksi->begin_transaction();
        try {
            // Kurangi poin pengguna
            $sql = "UPDATE profil SET total_point = total_point - ? WHERE id_user = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ii", $total_harga, $id_user);
            $stmt->execute();
            $stmt->close();

            // Catat pengurangan poin
            $point_with_min = '-' . $total_harga; // Menambahkan lambang + sebelum angka point
            $sql = "INSERT INTO point (id_user, point, jenis_transaksi, updated_at) VALUES (?, ?, 'Pembelian Souvenir', NOW())";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("is", $id_user, $point_with_min);
            $stmt->execute();
            $id_point = $stmt->insert_id;
            $stmt->close();

            // Buat order baru
            $sql = "INSERT INTO `order` (id_user, id_souvenir, id_point, tanggal_order, total_harga, status, jenis_order) VALUES (?, ?, ?, NOW(), ?, 'completed', 'souvenir')";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("iiii", $id_user, $id_souvenir, $id_point, $total_harga);
            $stmt->execute();
            $order_id = $stmt->insert_id;
            $stmt->close();

            // Catat pembayaran
            $sql = "INSERT INTO pembayaran (id_order, tanggal_pembayaran, jumlah_pembayaran, metode) VALUES (?, NOW(), ?, 'Pembayaran Point')";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ii", $order_id, $total_harga);
            $stmt->execute();
            $stmt->close();

            // Kurangi stok
            $sql = "UPDATE souvenir SET stok = stok - ? WHERE id_souvenir = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ii", $jumlah, $id_souvenir);
            $stmt->execute();
            $stmt->close();

            $koneksi->commit();
            echo "<script>alert('Pembelian berhasil.'); window.location.href='user_home.php';</script>";
        } catch (Exception $e) {
            $koneksi->rollback();
            echo "Error: " . $koneksi->error;
        }
    } else {
        echo "<script>alert('Poin tidak cukup.'); window.history.back();</script>";
    }
}
?>
