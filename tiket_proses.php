<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_event = $_POST['event'];
    $id_user = $_SESSION['id_user'];

    // Ambil harga dan stok tiket dari database berdasarkan id_event
    $sql = "SELECT t.id_tiket, t.harga, t.stok, e.nama_event, e.tanggal_event FROM tiket t JOIN event e ON t.id_event = e.id_event WHERE t.id_event = ?";
    $stmt = $koneksi->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: " . $koneksi->error;
        exit;
    }
    $stmt->bind_param("i", $id_event);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $id_tiket = $data['id_tiket'];
        $harga_tiket = $data['harga'];
        $stok_tiket = $data['stok'];
        $nama_event = $data['nama_event'];
        $tanggal_event = $data['tanggal_event'];
    } else {
        echo "<script>alert('Tiket tidak ditemukan.'); window.history.back();</script>";
        exit;
    }
    $stmt->close();

    // Cek jika user memiliki cukup poin
    $sql = "SELECT total_point FROM profil WHERE id_user = ?";
    $stmt = $koneksi->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: " . $koneksi->error;
        exit;
    }
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $total_point = $data['total_point'];
    $stmt->close();

    if ($total_point >= $harga_tiket && $stok_tiket > 0) {
        $koneksi->begin_transaction();
        try {
            // Kurangi poin pengguna
            $sql = "UPDATE profil SET total_point = total_point - ? WHERE id_user = ?";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $koneksi->error);
            }
            $stmt->bind_param("ii", $harga_tiket, $id_user);
            $stmt->execute();
            $stmt->close();

            // Catat pengurangan poin
            $point_with_min = '-' . $harga_tiket; // Menambahkan lambang - sebelum angka point
            $sql = "INSERT INTO point (id_user, point, jenis_transaksi, updated_at) VALUES (?, ?, 'Pembelian Tiket', NOW())";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $koneksi->error);
            }
            $stmt->bind_param("is", $id_user, $point_with_min);
            $stmt->execute();
            $id_point = $stmt->insert_id;
            $stmt->close();

            // Kurangi stok tiket
            $sql = "UPDATE tiket SET stok = stok - 1 WHERE id_event = ?";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $koneksi->error);
            }
            $stmt->bind_param("i", $id_event);
            $stmt->execute();
            $stmt->close();

            // Catat order tiket
            $sql = "INSERT INTO `order` (id_user, id_tiket, id_point, tanggal_order, total_harga, status, jenis_order) VALUES (?, ?, ?, NOW(), ?, 'completed', 'tiket')";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $koneksi->error);
            }
            $stmt->bind_param("iiii", $id_user, $id_tiket, $id_point, $harga_tiket);
            $stmt->execute();
            $order_id = $stmt->insert_id;
            $stmt->close();

            // Catat pembayaran
            $sql = "INSERT INTO pembayaran (id_order, tanggal_pembayaran, jumlah_pembayaran, metode) VALUES (?, NOW(), ?, 'Pembayaran Point')";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $koneksi->error);
            }
            $stmt->bind_param("ii", $order_id, $harga_tiket);
            $stmt->execute();
            $stmt->close();

            $koneksi->commit();
            echo "<script>alert('Pembelian tiket berhasil.'); window.location.href='user_home.php';</script>";
        } catch (Exception $e) {
            $koneksi->rollback();
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "<script>alert('Poin tidak cukup atau tiket sudah habis.'); window.history.back();</script>";
    }
}
?>
