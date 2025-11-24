<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika admin sudah login dan mencoba buka halaman login → kembalikan ke dashboard
if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'bank')) {
    header("Location: ../dashboard/index.php?msg=Anda+sudah+login");
    exit;
}
?>
