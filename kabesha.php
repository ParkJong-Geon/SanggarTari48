<?php
session_start();
include 'koneksi.php';

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

// Mengambil daftar file yang telah diupload
$sql = "SELECT kode_doc, deskripsi, file FROM kabesha";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kabesha</title>
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
        .table-container {
            margin-top: 30px;
        }
        .table {
            background-color: #fff;
        }
        .table thead th {
            background-color: #ffccd5;
            color: #c00;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #ffe6ea;
        }
        .table tbody tr:nth-child(even) {
            background-color: #fff;
        }
        .table tbody tr:hover {
            background-color: #ffb3c1;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px;
            width: 200px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card img {
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            width: 100%;
            height: auto;
        }
        .card-body {
            padding: 10px;
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
        <div class="table-container">
            <h1 class="text-center mt-4">Sanggar Tari Kabesha</h1>
            <div class="card-container">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <a href="kabesha_proses.php?kode_doc=<?php echo $row['kode_doc']; ?>">
                            <img src="<?php echo $row['file']; ?>" alt="Document Image">
                        </a>
                        <div class="card-body">
                            <p class="card-text"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
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
$koneksi->close();
?>
