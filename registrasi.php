<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-form {
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
    if (isset($_GET['error']) && $_GET['error'] == 'username_exists') {
        echo "<div class='alert alert-danger text-center' role='alert'>Username telah digunakan. Silakan coba username lain.</div>";
    }
    ?>
    <div class="container">
        <form method="POST" action="registrasi_proses.php" class="register-form">
            <h2 class="text-center">Register</h2>
            <div>
                <input type="hidden" id="role" name="role" value="2">
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Ulangi Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-custom btn-block">Submit</button>
            </div>
        </form>
        <p class="text-center">Sudah punya akun? <a href="login.php">Login</a></p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
    