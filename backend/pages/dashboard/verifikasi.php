<?php
include '../../../config/connection.php';
// session_start();

// Pastikan admin login
// if (!isset($_SESSION['admin'])) {
//     echo json_encode([
//         "success" => false,
//         "message" => "Admin belum login"
//     ]);
//     exit;
// }

$id = $_GET['id'] ?? null;
$id_admin = $_SESSION['admin']['id_admin'];

if (!$id) {
    echo json_encode([
        "success" => false,
        "message" => "ID pembayaran tidak ditemukan"
    ]);
    exit;
}

$q = "UPDATE pembayaran 
      SET status='LUNAS', id_admin='$id_admin'
      WHERE id_pembayaran='$id'";

if (mysqli_query($connect, $q)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode([
        "success" => false,
        "message" => mysqli_error($connect)
    ]);
}
?>
