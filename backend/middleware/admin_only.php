<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/login.php?need_admin=1");
    exit;
}
?>
