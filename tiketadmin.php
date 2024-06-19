<?php
session_start();
include 'koneksi.php';

// Memeriksa apakah pengguna sudah login dan apakah role adalah admin
if (!isset($_SESSION['id_user']) || $_SESSION['role_id'] != 1) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Harga Tiket Event</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #fff;
        }
        .navbar {
            background-color: #c00;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
            font-weight: bold;
        }
        .container {
            margin-top: 20px;
        }
        .btn-custom {
            background-color: #ff3366;
            color: white;
        }
        .btn-custom:hover {
            background-color: #ff6699;
        }
        .btn-danger {
            background-color: #ff3366;
            border-color: #ff3366;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="admin_home.php">Sanggar Tari 48</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="eventadmin.php">Pengeditan Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="venueadmin.php">Pengeditan Venue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pointadmin.php">Pengaproval Point</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tiketadmin.php">Pengeditan Tiket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="souveniradmin.php">Pengeditan Souvenir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="riwayatadmin.php">Riwayat Pembelian User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kabeshaadmin.php">Tempat Upload Kabesha</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-5 mb-4">Managemen Tiket Event</h1>
        <form action="tiketadmin_proses.php" method="post" class="mb-4">
            <div class="form-group">
                <label for="event">Pilih Event:</label>
                <select id="event" name="id_event" class="form-control">
                    <?php
                    $sql = "SELECT id_event, nama_event FROM event";
                    $result = $koneksi->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id_event'] . "'>" . $row['nama_event'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="harga">Harga Tiket (Point):</label>
                <input type="number" id="harga" name="harga" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="stok">Stok Tiket:</label>
                <input type="number" id="stok" name="stok" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-custom">Submit</button>
        </form>

        <h2 class="mt-5">Daftar Harga Tiket Event</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Nama Event</th>
                        <th>Harga Tiket</th>
                        <th>Stok Tiket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT e.nama_event, COALESCE(t.harga, 0) AS harga, COALESCE(t.stok, 0) AS stok 
                            FROM event e 
                            LEFT JOIN tiket t ON e.id_event = t.id_event";
                    $result = $koneksi->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['nama_event'] . "</td>
                                <td>" . $row['harga'] . " Point</td>
                                <td>" . $row['stok'] . "</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="admin_home.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$koneksi->close();
?>
