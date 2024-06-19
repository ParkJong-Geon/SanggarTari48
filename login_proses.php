<?php
include "koneksi.php";
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

// Query untuk memverifikasi username dan mengambil data user
$sql = "SELECT id_user, username, password, id_role FROM user WHERE username = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Jika tidak ada user dengan username tersebut
    header('Location: login.php?error=username_invalid');
    exit();
}

$user = $result->fetch_assoc();

// Verifikasi password
if (!password_verify($password, $user['password'])) {
    // Jika password tidak cocok
    header('Location: login.php?error=password_invalid');
    exit();
}

// Jika username dan password cocok
$_SESSION['id_user'] = $user['id_user']; // Menyimpan id_user dalam session
$_SESSION['username'] = $user['username'];
$_SESSION['role_id'] = $user['id_role'];  // Menyimpan id_role dalam session

// Redirect berdasarkan role
if ($user['id_role'] == 1) {
    // Role ID 1 untuk admin
    header('Location: admin_home.php');
} else {
    // Role ID 2 untuk user biasa
    header('Location: user_home.php');
}
?>
