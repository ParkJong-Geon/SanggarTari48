<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['id_user'] == 0) {
    header('Location: login.php');
    exit;
}

// Mengambil ID user dari session
$id_user = $_SESSION['id_user'];

// Query untuk mengambil data profil
$sql = "SELECT nama, email, telp FROM profil WHERE id_user = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$profil = $result->fetch_assoc();

$nama = htmlspecialchars($profil['nama'] ?? '');
$email = htmlspecialchars($profil['email'] ?? '');
$telp = htmlspecialchars($profil['telp'] ?? '');

$stmt->close();
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbarui Profil</title>
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
        .btn-custom {
            background-color: #ff3366;
            color: white;
        }
        .btn-custom:hover {
            background-color: #ff6699;
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
        <h1 class="text-center mt-4">Perbarui Profil</h1>
        <div class="card mt-4">
            <div class="card-body">
                <form action="profilupdate_proses.php" method="POST">
                    <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="telp">Telepon:</label>
                        <input type="text" class="form-control" id="telp" name="telp" value="<?php echo $telp; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-custom">Perbarui Profil</button>
                    <a href="profil.php" class="btn btn-secondary">Batal</a>
                </form>
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
