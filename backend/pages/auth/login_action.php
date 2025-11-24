<?php
session_start();
include '../../../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $role = mysqli_real_escape_string($connect, $_POST['role']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = $_POST['password'];

    // validasi role
    if (!in_array($role, ['admin', 'pelanggan'])) {
        $_SESSION['msg'] = "Role tidak valid!";
        header("Location: login.php");
        exit;
    }

    // Pilih tabel
    $table = ($role === 'admin') ? 'admin' : 'pelanggan';

    // Query berdasarkan role
    $query = mysqli_query($connect, "SELECT * FROM $table WHERE email='$email' LIMIT 1");

    if (mysqli_num_rows($query) === 1) {

        $user = mysqli_fetch_assoc($query);

        // Cocokkan password
        if (password_verify($password, $user['password'])) {

            // LOGIN ADMIN
            if ($role === 'admin') {

                $_SESSION['id_admin'] = $user['id_admin'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email']    = $user['email'];
                $_SESSION['name']     = $user['nama_admin'];
                $_SESSION['role']     = 'admin';

                $_SESSION['msg'] = "Login admin berhasil! Selamat datang, {$user['nama_admin']}";

                header("Location: ../dashboard/index.php");
                exit;
            }

            // LOGIN BANK (JIKA ADA)
            if ($role === 'bank') {

                $_SESSION['id_bank'] = $user['id_bank'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['nama_bank'];
                $_SESSION['role'] = 'bank';

                $_SESSION['msg'] = "Login bank berhasil! Selamat datang, {$user['nama_bank']}";

                header("Location: ../dashboard/index.php");
                exit;
            }

            // LOGIN PELANGGAN
            else {

                $_SESSION['id_pelanggan'] = $user['id_pelanggan'];
                $_SESSION['username']     = $user['username'];
                $_SESSION['email']        = $user['email'];
                $_SESSION['name']         = $user['nama_pelanggan'];
                $_SESSION['role']         = 'pelanggan';

                $_SESSION['msg'] = "Login pelanggan berhasil! Selamat datang, {$user['nama_pelanggan']}";

                header("Location: ../../../frontend/pages/index.php");
                exit;
            }
        } else {

            $_SESSION['msg'] = "Password salah!";
            header("Location: login.php");
            exit;
        }
    } else {

        $_SESSION['msg'] = "Email tidak terdaftar!";
        header("Location: login.php");
        exit;
    }
}
