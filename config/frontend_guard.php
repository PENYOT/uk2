<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika belum login → larang akses halaman pelanggan
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../pages/login.php?msg=Silakan+login+untuk+mengakses+halaman+ini");
    exit;
}
?>
