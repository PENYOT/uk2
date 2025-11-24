<?php
session_start();
include '../../../config/login_guard.php';  // CEGAH ADMIN AKSES LOGIN SAAT SUDAH LOGIN
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Listrik</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            background: linear-gradient(135deg, #007bff, #6610f2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Poppins", sans-serif;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 35px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .2);
            text-align: center;
        }

        .login-box h3 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .btn-login {
            border-radius: 10px;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <img src="../../../storages/1.png" width="70" class="mb-3">

        <h3>Login Sistem</h3>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-warning py-2">
                <?= $_SESSION['msg'];
                unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login_action.php">

            <div class="mb-3 text-start">
                <label class="fw-semibold">Masuk Sebagai</label>
                <select name="role" class="form-select" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin / Staf</option>
                    <option value="pelanggan">Pelanggan</option>
                </select>
            </div>

            <div class="mb-3 text-start">
                <label class="fw-semibold">Email</label>
                <input type="email" class="form-control" name="email" required placeholder="Masukkan email">
            </div>

            <div class="mb-3 text-start">
                <label class="fw-semibold">Password</label>
                <input type="password" class="form-control" name="password" required placeholder="Masukkan password">
            </div>

            <button class="btn btn-primary btn-login w-100 py-2">Login</button>
        </form>

        <p class="mt-4 text-muted small">© <?= date("Y") ?> Sistem Pembayaran Listrik</p>
    </div>

</body>

</html>