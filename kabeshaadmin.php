<?php
session_start();
include 'koneksi.php';

// Memeriksa apakah pengguna sudah login dan apakah role adalah admin
if (!isset($_SESSION['id_user']) || $_SESSION['role_id'] != 1) {
    header('Location: login.php');
    exit;
}

// Mengambil daftar file yang telah diupload
$sql = "SELECT kode_doc, deskripsi, file, nama_file, tipe_file, ukuran_file FROM kabesha";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen</title>
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
        .btn-custom {
            background-color: #ff3366;
            color: white;
        }
        .btn-custom:hover {
            background-color: #ff6699;
        }
        .container {
            margin-top: 20px;
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
    <script>
        function addFileInput() {
            const fileInputContainer = document.getElementById('file-input-container');
            const index = fileInputContainer.children.length;

            const fileInputDiv = document.createElement('div');
            fileInputDiv.setAttribute('class', 'file-input form-group');

            const deskripsiLabel = document.createElement('label');
            deskripsiLabel.setAttribute('for', `deskripsi${index}`);
            deskripsiLabel.innerText = 'Deskripsi:';
            fileInputDiv.appendChild(deskripsiLabel);

            const deskripsiInput = document.createElement('input');
            deskripsiInput.setAttribute('type', 'text');
            deskripsiInput.setAttribute('id', `deskripsi${index}`);
            deskripsiInput.setAttribute('name', `deskripsi[]`);
            deskripsiInput.setAttribute('class', 'form-control');
            deskripsiInput.required = true;
            fileInputDiv.appendChild(deskripsiInput);

            const fileLabel = document.createElement('label');
            fileLabel.setAttribute('for', `file${index}`);
            fileLabel.innerText = 'Pilih File:';
            fileInputDiv.appendChild(fileLabel);

            const fileInput = document.createElement('input');
            fileInput.setAttribute('type', 'file');
            fileInput.setAttribute('id', `file${index}`);
            fileInput.setAttribute('name', `file[]`);
            fileInput.setAttribute('class', 'form-control-file');
            fileInput.required = true;
            fileInputDiv.appendChild(fileInput);

            fileInputContainer.appendChild(fileInputDiv);
        }
    </script>
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
        <h1 class="mt-5 mb-4">Upload Dokumen</h1>
        <form action="kabeshaadmin_proses.php" method="post" enctype="multipart/form-data">
            <div id="file-input-container" class="form-group">
                <div class="file-input form-group">
                    <label for="deskripsi0">Deskripsi:</label>
                    <input type="text" id="deskripsi0" name="deskripsi[]" class="form-control" required>
                    <br>
                    <label for="file0">Pilih File:</label>
                    <input type="file" id="file0" name="file[]" class="form-control-file" required>
                    <br>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addFileInput()">Tambah File Lain</button>
            <br><br>
            <button type="submit" class="btn btn-custom" name="upload">Upload</button>
        </form>

        <h2 class="mt-5">Daftar Dokumen</h2>
        <div class="card-container mb-4">
            <?php
            $result->data_seek(0); // Reset the result pointer to the beginning
            while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                        <img src="<?php echo $row['file']; ?>" alt="Document Image">
                    </a>
                    <div class="card-body">
                        <p class="card-text"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <form action="kabeshaadmin_proses.php" method="post">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Pilih</th>
                            <th>Deskripsi</th>
                            <th>Nama File</th>
                            <th>Tipe File</th>
                            <th>Ukuran File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result->data_seek(0); // Reset the result pointer to the beginning
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><input type="checkbox" name="kode_doc[]" value="<?php echo $row['kode_doc']; ?>"></td>
                                <td><?php echo $row['deskripsi']; ?></td>
                                <td><?php echo $row['nama_file']; ?></td>
                                <td><?php echo $row['tipe_file']; ?></td>
                                <td><?php echo $row['ukuran_file']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-danger" name="delete">Delete Selected</button>
        </form>
        
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
