<?php
include "koneksi.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$role = $_POST['role'];  // Mengambil ID role dari form

// Mengecek apakah password yang dimasukkan sama dengan confirm_password
if ($password !== $confirm_password) {
    echo "<script>alert('Password tidak sama. Silakan ulangi.'); window.history.back();</script>";
    exit();
}

// Hash password dengan bcrypt
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Mengecek apakah username sudah ada di database
$sqlCheck = "SELECT username FROM user WHERE username = ?";
$stmtCheck = $koneksi->prepare($sqlCheck);
$stmtCheck->bind_param("s", $username);
$stmtCheck->execute();
$stmtCheck->store_result();

if ($stmtCheck->num_rows > 0) {
    // Jika username sudah ada, kembalikan ke halaman registrasi dengan pesan error
    header('Location: registrasi.php?error=username_exists');
    $stmtCheck->close();
    exit();
}
$stmtCheck->close();

// Menyimpan data pengguna jika username belum ada
$sqlInsert = "INSERT INTO user (username, password, id_role) VALUES (?, ?, ?)";
$stmtInsert = $koneksi->prepare($sqlInsert);
$stmtInsert->bind_param("ssi", $username, $hashed_password, $role);

if ($stmtInsert->execute()) {
    // Mendapatkan id_user yang baru disisipkan
    $last_id = $stmtInsert->insert_id;
    
    // Menyimpan entry kosong ke dalam tabel profil
    $sqlProfile = "INSERT INTO profil (id_user, nama, email, telp, total_point) VALUES (?, '', '', '','0')";
    $stmtProfile = $koneksi->prepare($sqlProfile);
    $stmtProfile->bind_param("i", $last_id);
    $stmtProfile->execute();
    $stmtProfile->close();

    echo "<script>alert('Anda berhasil registrasi. Silakan login.'); window.location.href='login.php';</script>";
} else {
    echo "Error: " . $stmtInsert->error;
}
$stmtInsert->close();
$koneksi->close();
?>
