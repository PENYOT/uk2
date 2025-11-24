<?php
session_start();
include '../../../config/connection.php'; // sesuaikan path sesuai struktur proyekmu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validasi role
    $allowed_roles = ['admin', 'staff'];
    if (!in_array($role, $allowed_roles)) {
        header('Location: register.php?error=Role tidak valid');
        exit;
    }

    // Cek email sudah terdaftar
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $checkEmailResult = mysqli_query($connect, $checkEmailQuery);
    if (mysqli_num_rows($checkEmailResult) > 0) {
        header('Location: register.php?error=Email sudah terdaftar');
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert ke tabel users
    $insertQuery = "INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES ('$username', '$email', '$hashedPassword', '$role', NOW(), NOW())";
    if (mysqli_query($connect, $insertQuery)) {
        header('Location: register.php?success=Registrasi berhasil, silakan login.');
    } else {
        header('Location: register.php?error=Registrasi gagal, coba lagi.');
    }
} else {
    header('Location: register.php');
    exit;
}
