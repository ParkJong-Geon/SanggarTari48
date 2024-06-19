<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group label {
            font-weight: bold;
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
    <?php
    include "koneksi.php"; // Pastikan file ini berisi koneksi ke database Anda.
    if (isset($_GET['error'])) {
        $errorMessage = '';
        if ($_GET['error'] == 'password_invalid') {
            $errorMessage = "Password salah. Silakan coba lagi.";
        } elseif ($_GET['error'] == 'username_invalid') {
            $errorMessage = "Username tidak ditemukan. Silakan coba lagi.";
        }

        // Menambahkan script untuk menampilkan alert
        echo "<div class='alert alert-danger text-center' role='alert'>$errorMessage</div>";
    }
    ?>

    <div class="container">
        <form action="login_proses.php?op=in" method="POST" class="login-form">
            <h2 class="text-center">Login</h2>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" name="login" class="btn btn-custom btn-block">Login</button>
            </div>
        </form>
        <p class="text-center">Belum punya akun? <a href="registrasi.php">Registrasi Disini</a></p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
