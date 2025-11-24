<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin']['role'] !== "Bank") {
    header("Location: ../dashboard/index.php?forbidden=1");
    exit;
}
?>
