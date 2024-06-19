<?php
session_start();
include 'koneksi.php';
// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    // Jika tidak ada session 'id_user', pengguna tidak login, redirect ke halaman login
    header('Location: login.php');
    exit;
}
// Cek apakah role pengguna adalah 'user'
if ($_SESSION['role_id'] != 2) { // Misalkan '2' adalah role_id untuk 'user'
    // Jika bukan user, redirect ke halaman lain atau tampilkan pesan error
    header('Location: login.php'); // Adjust redirect based on your application
    exit;
}


// Mengambil ID user dari session
$user_id = $_SESSION['id_user'];
$sql = "SELECT username FROM user WHERE id_user = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanggar Tari 48 | Halaman Utama</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #c00;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
            font-weight: bold;
        }
        .carousel-inner img {
            width: 100%;
            height: 650px; /* Adjust the height as needed */
            object-fit: cover; /* Ensures the image covers the element */
        }
        .btn-custom {
            background-color: #ff3366;
            color: white;
        }
        .btn-custom:hover {
            background-color: #ff6699;
        }
        .footer {
            background-color: #c00;
            color: white;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
            text-align: center;
        }
        .content {
            margin-bottom: 80px; /* Space for the footer */
        }
        .carousel-container {
            margin-bottom: 30px; /* Space between carousel and content below */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="user_home.php">Sanggar Tari 48</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profil.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="event.php">Info Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="point.php">Pembelian Point</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tiket.php">Pembelian Tiket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="souvenir.php">Pembelian Souvenir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="riwayat.php">Riwayat Pembelian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kabesha.php">Tempat Download Kabesha</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container content">
        <div id="carouselExampleIndicators" class="carousel slide mt-4 carousel-container" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <a href="event.php"><img src="home_pajamadrive.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5></h5>
                        <p></p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="aac.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5></h5>
                        <p></p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="jkt48v.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5></h5>
                        <p></p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <div class="card-deck mt-5">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Event Terbaru</h5>
                    <p class="card-text">Lihat informasi event terbaru yang akan datang.</p>
                    <a href="event.php" class="btn btn-custom">Info Event</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Beli Tiket</h5>
                    <p class="card-text">Pesan tiket untuk event yang ingin Anda hadiri.</p>
                    <a href="tiket.php" class="btn btn-custom">Pembelian Tiket</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Beli Souvenir</h5>
                    <p class="card-text">Dapatkan souvenir menarik dari event yang Anda hadiri.</p>
                    <a href="souvenir.php" class="btn btn-custom">Pembelian Souvenir</a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 Sanggar Tari 48. All Rights Reserved.</p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
/*
$stmt->close();
$koneksi->close();
?>
*/