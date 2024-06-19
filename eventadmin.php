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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Event</title>
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
        <h1 class="mt-5 mb-4">Manajemen Event</h1>
        <form method="POST" action="eventadmin_proses.php" class="mb-4">
            <input type="hidden" name="id_event" id="id_event" value="">
            <div class="form-group">
                <label for="nama_event">Nama Event:</label>
                <input type="text" class="form-control" id="nama_event" name="nama_event" required>
            </div>
            <div class="form-group">
                <label for="tanggal_event">Tanggal Event:</label>
                <input type="text" class="form-control" id="tanggal_event" name="tanggal_event" required>
            </div>
            <div class="form-group">
                <label for="venue">Venue:</label>
                <select id="venue" class="form-control" name="venue">
                    <?php
                    $query = "SELECT * FROM venue";
                    $result = $koneksi->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['id_venue']) . '">' . htmlspecialchars($row['nama_venue']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-custom" name="action" value="add">Tambah Event</button>
                <button type="submit" class="btn btn-custom" name="action" value="update">Update Event</button>
            </div>
        </form>
        <hr>
        <!-- Tabel untuk menampilkan event -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Nama Event</th>
                        <th>Tanggal Event</th>
                        <th>Nama Venue</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT e.id_event, e.nama_event, e.tanggal_event, v.nama_venue, v.id_venue 
                            FROM event e
                            JOIN venue v ON e.id_venue = v.id_venue";
                    $result = $koneksi->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['nama_event']) . "</td>
                                <td>" . htmlspecialchars($row['tanggal_event']) . "</td>
                                <td>" . htmlspecialchars($row['nama_venue']) . "</td>
                                <td>
                                    <button class='btn btn-sm btn-warning' onclick=\"fillForm('{$row['id_event']}', '{$row['nama_event']}', '{$row['tanggal_event']}', '{$row['id_venue']}')\">Pilih</button>
                                    <form method='post' action='eventadmin_proses.php' onsubmit='return confirm(\"Are you sure?\");' style='display:inline;'>
                                        <input type='hidden' name='id_event' value='{$row['id_event']}'>
                                        <input type='hidden' name='action' value='delete'>
                                        <button type='submit' class='btn btn-sm btn-danger'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="admin_home.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>

    <script>
        function fillForm(id, nama, tanggal, venue) {
            document.getElementById('id_event').value = id;
            document.getElementById('nama_event').value = nama;
            document.getElementById('tanggal_event').value = tanggal;
            document.getElementById('venue').value = venue;
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
