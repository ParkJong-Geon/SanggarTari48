<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['id_user']) || $_SESSION['id_user'] == 0) {
    header('Location: login.php');
    exit;
}
// Mengambil ID user dari session
$user_id = $_SESSION['id_user'];

// Ambil ID event dari request (misalnya melalui GET atau POST)
$id_event = $_POST['id_event'] ?? 0; 

// Query untuk mengambil data profil
$sql = "SELECT nama_event, tanggal_event, telp FROM event WHERE id_user = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id_event);
$stmt->execute();
$result = $stmt->get_result();
$profil = $result->fetch_assoc();
?>
