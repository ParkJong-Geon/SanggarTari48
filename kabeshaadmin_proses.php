<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role_id'] != 1) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['upload'])) {
        $deskripsi = $_POST['deskripsi'];
        $files = $_FILES['file'];

        $jumlah_file = count($files['name']);

        for ($i = 0; $i < $jumlah_file; $i++) {
            // Validasi ukuran file
            if ($files['size'][$i] > 2000000) {
                echo "<script>alert('Ukuran file terlalu besar.'); window.history.back();</script>";
                exit;
            }

            $nama_file = $files['name'][$i];
            $tipe_file = $files['type'][$i];
            $ukuran_file = $files['size'][$i];
            $target_dir = "kabesha/";

            $target_file = $target_dir . basename($files["name"][$i]);

            // Pindahkan file ke direktori target
            if (move_uploaded_file($files["tmp_name"][$i], $target_file)) {
                // Simpan jalur file ke database
                $sql = "INSERT INTO kabesha (deskripsi, file, nama_file, tipe_file, ukuran_file) VALUES (?, ?, ?, ?, ?)";
                $stmt = $koneksi->prepare($sql);
                if (!$stmt) {
                    echo "Error preparing statement: " . $koneksi->error;
                    exit;
                }
                $stmt->bind_param("ssssi", $deskripsi[$i], $target_file, $nama_file, $tipe_file, $ukuran_file);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "<script>alert('Terjadi kesalahan saat mengupload file.'); window.history.back();</script>";
                exit;
            }
        }

        echo "<script>alert('File berhasil diupload.'); window.location.href='kabeshaadmin.php';</script>";
    }

    if (isset($_POST['delete']) && isset($_POST['kode_doc'])) {
        $kode_docs = $_POST['kode_doc'];
        foreach ($kode_docs as $kode_doc) {
            // Hapus file dari direktori
            $sql = "SELECT file FROM kabesha WHERE kode_doc = ?";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                echo "Error preparing statement: " . $koneksi->error;
                exit;
            }
            $stmt->bind_param("i", $kode_doc);
            $stmt->execute();
            $stmt->bind_result($file_path);
            $stmt->fetch();
            $stmt->close();
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Hapus data dari database
            $sql = "DELETE FROM kabesha WHERE kode_doc = ?";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                echo "Error preparing statement: " . $koneksi->error;
                exit;
            }
            $stmt->bind_param("i", $kode_doc);
            $stmt->execute();
            $stmt->close();
        }
        echo "<script>alert('File berhasil dihapus.'); window.location.href='kabeshaadmin.php';</script>";
    }
}
?>
