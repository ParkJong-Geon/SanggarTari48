<?php
session_start();  // Memulai session untuk mengakses variabel session

// Menghapus semua variabel session
$_SESSION = array();

// Menghancurkan session
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit;
?>
