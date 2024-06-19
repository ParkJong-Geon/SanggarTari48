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
    <title>Pembelian Souvenir</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #c00;
        }

        .navbar-brand,
        .navbar-nav .nav-link {
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
            margin-top: 30px;
        }

        .btn-custom {
            background-color: #ff3366;
            color: white;
        }

        .btn-custom:hover {
            background-color: #ff6699;
        }

        .img-container {
            text-align: center;
            margin-top: 20px;
        }

        .img-container img {
            max-width: 300px;
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: block;
            margin: 0 auto; /* Tambahkan ini untuk memastikan gambar berada di tengah */
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
        <h1 class="text-center mt-4">Pembelian Souvenir</h1>
        <form action="souvenir_proses.php" method="post" class="mt-4">
            <div class="form-group">
                <label for="id_souvenir">Pilih Souvenir</label>
                <select name="id_souvenir" id="id_souvenir" class="form-control" required onchange="showImage()">
                    <?php
                    include 'koneksi.php';
                    $query = "SELECT * FROM souvenir WHERE stok > 0";
                    $result = $koneksi->query($query);
                    while ($row = $result->
                    fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['id_souvenir']) . '" data-img="souvenir/' . htmlspecialchars($row['gambar']) . '">' . htmlspecialchars($row['nama_souvenir']) . ' - ' . htmlspecialchars($row['harga']) . ' Points' . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" placeholder="Jumlah" required>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Beli</button>
        </form>
        <div class="text-center mt-4">
            <a href="user_home.php" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="img-container">
            <img id="souvenirImage" src="" alt="Gambar Souvenir" style="display: none;">
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 Sanggar Tari 48. All Rights Reserved.</p>
    </div>

    <script>
        function showImage() {
            var select = document.getElementById('id_souvenir');
            var image = select.options[select.selectedIndex].getAttribute('data-img');
            var imgElement = document.getElementById('souvenirImage');
            if (image) {
                imgElement.src = image;
                imgElement.style.display = 'block';
            } else {
                imgElement.style.display = 'none';
            }
        }
    </script>

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
