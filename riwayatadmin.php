<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role_id'] != 1) {
    header('Location: login.php');
    exit;
}

// Set current page and calculate offset
$limit = 10; // Number of entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get the total number of records
$total_sql = "SELECT COUNT(*) as count FROM `order` o";
$total_result = $koneksi->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['count'];
$total_pages = ceil($total_records / $limit);

// Get the records for the current page
$sql = "SELECT o.id_order, o.tanggal_order, o.total_harga, o.status, o.jenis_order,
               u.username, p.nama, p.email, p.telp,
               CASE 
                   WHEN o.jenis_order = 'point' THEN 'Point'
                   WHEN o.jenis_order = 'tiket' THEN 'Tiket'
                   WHEN o.jenis_order = 'souvenir' THEN s.nama_souvenir
                   ELSE 'Unknown'
               END AS nama_barang,
               COALESCE(t.harga, s.harga, o.total_harga) AS harga,
               pay.tanggal_pembayaran, po.point, pay.metode
        FROM `order` o
        LEFT JOIN user u ON o.id_user = u.id_user
        LEFT JOIN profil p ON o.id_user = p.id_user
        LEFT JOIN tiket t ON o.id_tiket = t.id_tiket
        LEFT JOIN souvenir s ON o.id_souvenir = s.id_souvenir
        LEFT JOIN pembayaran pay ON o.id_order = pay.id_order
        LEFT JOIN point po ON o.id_point = po.id_point
        LIMIT $limit OFFSET $offset";

$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembelian Admin</title>
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
        <h1 class="mt-5 mb-4">Riwayat Pembelian Semua User</h1>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Tanggal Order</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Jumlah Pembayaran</th>
                        <th>Metode</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['id_order']; ?></td>
                            <td><?php echo $row['tanggal_order']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['telp']; ?></td>
                            <td><?php echo $row['nama_barang']; ?></td>
                            <td><?php echo $row['harga']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['tanggal_pembayaran']; ?></td>
                            <td><?php echo $row['point']; ?></td>
                            <td><?php echo $row['metode']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination Links -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
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
