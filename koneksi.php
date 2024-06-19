<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "uasweb";
$koneksi = mysqli_connect($host, $username, $password, $database);
if ($koneksi) {
    echo "";
} else {
    echo "Server gagal tersambung";
}
?>