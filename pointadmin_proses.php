<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role_id'] != 1) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $selected_orders = $_POST['selected_orders'];

    if (!empty($selected_orders)) {
        $koneksi->begin_transaction();
        try {
            foreach ($selected_orders as $id_order) {
                if ($action == 'approve') {
                    // Ambil informasi order
                    $sql = "SELECT id_user, total_harga FROM `order` WHERE id_order = ?";
                    $stmt = $koneksi->prepare($sql);
                    $stmt->bind_param("i", $id_order);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $order = $result->fetch_assoc();
                    $id_user = $order['id_user'];
                    $point = $order['total_harga']; // Misal, Rp 1.000 = 10 point.

                    // Tambahkan poin ke tabel point
                    $point_with_plus = '+' . $point; // Menambahkan lambang + sebelum angka point
                    $sql = "INSERT INTO point (id_user, point, jenis_transaksi, updated_at) VALUES (?, ?, 'Pengisian Point', NOW())";
                    $stmt = $koneksi->prepare($sql);
                    $stmt->bind_param("is", $id_user, $point_with_plus); // Menggunakan bind_param dengan tipe data string untuk point
                    $stmt->execute();
                    $id_point = $stmt->insert_id; // Dapatkan id_point yang baru dimasukkan
                    $stmt->close();

                    // Tambahkan poin ke profil pengguna
                    $sql = "UPDATE profil SET total_point = total_point + ? WHERE id_user = ?";
                    $stmt = $koneksi->prepare($sql);
                    $stmt->bind_param("ii", $point, $id_user);
                    $stmt->execute();
                    $stmt->close();

                    // Perbarui status order dan isi id_point di tabel order
                    $sql = "UPDATE `order` SET status = 'completed', id_point = ? WHERE id_order = ?";
                    $stmt = $koneksi->prepare($sql);
                    $stmt->bind_param("ii", $id_point, $id_order);
                    $stmt->execute();
                    $stmt->close();

                    // Insert pembayaran
                    $sql = "INSERT INTO pembayaran (id_order, tanggal_pembayaran, jumlah_pembayaran, metode) VALUES (?, NOW(), ?, 'Approval Admin')";
                    $stmt = $koneksi->prepare($sql);
                    $stmt->bind_param("ii", $id_order, $point);
                    $stmt->execute();
                    $stmt->close();
                } elseif ($action == 'reject') {
                    // Perbarui status order menjadi rejected
                    $sql = "UPDATE `order` SET status = 'rejected' WHERE id_order = ?";
                    $stmt = $koneksi->prepare($sql);
                    $stmt->bind_param("i", $id_order);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $koneksi->commit();
            echo "<script>alert('Aksi berhasil.'); window.location.href='pointadmin.php';</script>";
        } catch (Exception $e) {
            $koneksi->rollback();
            echo "<script>alert('Aksi gagal.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Tidak ada order yang dipilih.'); window.history.back();</script>";
    }
}
?>
