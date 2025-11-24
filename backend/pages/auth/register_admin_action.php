<?php
include '../../../config/connection.php';

// Validasi input
if (
    !isset($_POST['username']) ||
    !isset($_POST['email']) ||
    !isset($_POST['password']) ||
    !isset($_POST['nama_admin']) ||
    !isset($_POST['id_level'])
) {
    header("Location: register_admin.php?error=Form tidak lengkap");
    exit;
}

$username   = trim($_POST['username']);
$email      = trim($_POST['email']);
$nama       = trim($_POST['nama_admin']);
$id_level   = intval($_POST['id_level']);

// Hash password
$password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Query simpan admin
$q = "INSERT INTO admin (username, email, password, nama_admin, id_level)
      VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connect, $q);
mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $password, $nama, $id_level);

if (mysqli_stmt_execute($stmt)) {
    header("Location: register_admin.php?success=1");
    exit;
} else {
    header("Location: register_admin.php?error=Gagal menyimpan data");
    exit;
}
