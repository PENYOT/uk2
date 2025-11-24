<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Untuk keamanan, hanya admin & bank yang boleh masuk backend
$allowed_roles = ['admin', 'bank'];

// Jika belum login → tendang ke login
if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php?msg=Silakan+login+terlebih+dahulu");
    exit;
}

// Jika role tidak diizinkan → tendang
if (!in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../auth/login.php?msg=Akses+ditolak");
    exit;
}
?>
