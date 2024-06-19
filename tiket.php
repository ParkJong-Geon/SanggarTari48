<?php
session_start();
include 'koneksi.php';
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
    <title>Pembelian Tiket</title>
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
            text-align: center;
            margin-top: 20px;
        }
        .content {
            margin-top: 30px;
        }
        .btn-custom {
            background-color: #ff3366;
            color: white;
        }
        .btn-custom:hover {
            background-color: #ff6699;
        }
        .table-container {
            margin-top: 30px;
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
        <h1 class="text-center mt-4">Pembelian Tiket</h1>
        <form action="tiket_proses.php" method="post" class="mt-4">
            <div class="form-group">
                <label for="event">Pilih Event:</label>
                <select id="event" name="event" class="form-control" required>
                    <?php
                    include 'koneksi.php';
                    $sql = "SELECT id_event, nama_event, tanggal_event FROM event";
                    $result = $koneksi->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['id_event']) . "'>" . htmlspecialchars($row['nama_event']) . " - " . htmlspecialchars($row['tanggal_event']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Beli Tiket</button>
        </form>

        <div class="table-container">
            <h2 class="text-center mt-5">Daftar Harga Tiket Event</h2>
            <table class="table table-bordered table-striped mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th>Nama Event</th>
                        <th>Harga Tiket</th>
                        <th>Stok Tiket</th>
                        <th>Tanggal Event</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT e.nama_event, COALESCE(t.harga, 0) AS harga, COALESCE(t.stok, 0) AS stok, e.tanggal_event FROM event e LEFT JOIN tiket t ON e.id_event = t.id_event";
                    $result = $koneksi->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . htmlspecialchars($row['nama_event']) . "</td><td> " . htmlspecialchars($row['harga']) . " Point</td><td>". htmlspecialchars($row['stok']). " </td><td>". htmlspecialchars($row['tanggal_event']). " </td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-4">
            <a href="user_home.php" class="btn btn-secondary">Kembali</a>
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
$stmt->close();
$koneksi->close();
?>