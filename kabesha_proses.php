<?php
include 'koneksi.php';

if (isset($_GET['kode_doc'])) {
    $kode_doc = $_GET['kode_doc'];

    $sql = "SELECT nama_file, tipe_file, file FROM kabesha WHERE kode_doc = ?";
    $stmt = $koneksi->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: " . $koneksi->error;
        exit;
    }
    $stmt->bind_param("i", $kode_doc);
    $stmt->execute();
    $stmt->bind_result($nama_file, $tipe_file, $file_path);
    $stmt->fetch();

    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $tipe_file);
        header('Content-Disposition: attachment; filename="' . $nama_file . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        echo "File tidak ditemukan.";
    }
} else {
    echo "File tidak ditemukan.";
}
?>
