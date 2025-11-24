<?php
session_start();

// Simpan role sebelum logout
$role = $_SESSION['role'] ?? null;

// Hapus session total
session_unset();
session_destroy();

// Setelah logout, admin wajib login kembali
if ($role === 'admin' || $role === 'bank') {
    header("Location: login.php?msg=Silakan+login+kembali");
    exit;
}

// Pelanggan kembali ke halaman frontend
header("Location: ../../../frontend/pages/index.php?logout=success");
exit;
