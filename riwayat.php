<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['id_user'];

// Set current page and items per page
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Query to get total number of records
$sql_count = "SELECT COUNT(*) AS total FROM `order` WHERE id_user = ?";
$stmt_count = $koneksi->prepare($sql_count);
$stmt_count->bind_param("i", $id_user);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_items = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);

// Query to get paginated records
$sql = "SELECT o.id_order, o.tanggal_order, o.total_harga, o.status, o.jenis_order,
               CASE 
                   WHEN o.jenis_order = 'point' THEN 'Point'
                   WHEN o.jenis_order = 'tiket' THEN 'Tiket'
                   WHEN o.jenis_order = 'souvenir' THEN s.nama_souvenir
                   ELSE 'Unknown'
               END AS nama_barang,
               COALESCE(t.harga, s.harga, o.total_harga) AS harga,
               p.tanggal_pembayaran, po.point, p.metode
        FROM `order` o
        LEFT JOIN tiket t ON o.id_tiket = t.id_tiket
        LEFT JOIN souvenir s ON o.id_souvenir = s.id_souvenir
        LEFT JOIN pembayaran p ON o.id_order = p.id_order
        LEFT JOIN point po ON o.id_point = po.id_point
        WHERE o.id_user = ?
        LIMIT ? OFFSET ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("iii", $id_user, $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembelian</title>
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
        .table thead th {
            background-color: #ffccd5;
            color: #000;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #ffe6eb;
        }
        .table tbody tr:nth-child(even) {
            background-color: #fff;
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
            <h1 class="text-center mt-4">Riwayat Pembelian</h1>
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Tanggal Order</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Tanggal Pembayaran</th>
                            <th>Jumlah Pembayaran</th>
                            <th>Metode</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id_order'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['tanggal_order'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_barang'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['harga'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['status'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['tanggal_pembayaran'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['point'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['metode'] ?? ''); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                                <a class="page-link" href="riwayat.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php else: ?>
                <p class="text-center">Tidak ada riwayat pembelian yang tersedia.</p>
            <?php endif; ?>
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
