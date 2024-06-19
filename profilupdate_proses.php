
<?php
include 'koneksi.php';

$id_user = $_POST['id_user'] ?? 0;
$nama = $_POST['nama'] ?? '';
$email = $_POST['email'] ?? '';
$telp = $_POST['telp'] ?? '';

// Periksa apakah profil sudah ada
$sqlCheck = "SELECT id_user FROM profil WHERE id_user = ?";
$stmtCheck = $koneksi->prepare($sqlCheck);
$stmtCheck->bind_param("i", $id_user);
$stmtCheck->execute();
$stmtCheck->store_result();

if ($stmtCheck->num_rows > 0) {
    // Jika profil sudah ada, update
    $sqlUpdate = "UPDATE profil SET nama = ?, email = ?, telp = ? WHERE id_user = ?";
    $stmtUpdate = $koneksi->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sssi", $nama, $email, $telp, $id_user);
    $stmtUpdate->execute();
} else {
    echo "Profil tidak ditemukan, silakan hubungi admin.";
    // Potensial redirect atau tindakan lain
}

// Redirect kembali ke halaman profil
header('Location: profil.php');
exit();
?>
