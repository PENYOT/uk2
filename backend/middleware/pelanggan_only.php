<?php
session_start();

if (!isset($_SESSION['pelanggan'])) {
    header("Location: ../auth/login.php?need_login=1");
    exit;
}
?>
