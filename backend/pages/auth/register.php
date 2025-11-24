<?php
session_start();
if (isset($_SESSION['email'])) {
    echo "
    <script>
    alert('Anda harus logout dulu');
    window.location.href = '../dashboard/index.php';
    </script>
    ";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body,
        html {
            height: 100%;
            background: url('../../../storages/login/luffy.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.9);
            max-width: 400px;
            margin: 60px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .register-container h2 {
            margin-bottom: 25px;
            font-weight: 700;
            color: #333;
            text-align: center;
        }

        label {
            font-weight: 600;
        }

        .form-select {
            height: 45px;
        }

        .btn-register {
            background-color: #1c7ed6;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-register:hover {
            background-color: #1864ab;
        }

        .text-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .text-footer a {
            color: #1c7ed6;
            text-decoration: none;
            font-weight: 600;
        }

        .text-footer a:hover {
            text-decoration: underline;
        }

        .alert-message {
            margin-bottom: 20px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="register-container shadow">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-message"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-message"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <h2>Register Akun Baru</h2>

        <form action="register_action.php" method="post" novalidate>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required />
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" required />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required />
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="staff" selected>Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" id="remember" class="form-check-input" />
                    <label for="remember" class="form-check-label">Remember Me</label>
                </div>
                <a href="#" class="text-decoration-none">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-register w-100 py-2">Register</button>
        </form>

        <div class="text-footer">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (optional for some components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>