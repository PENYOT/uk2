<?php
session_start();
include '../../config/connection.php';

$id_tagihan   = $_POST['id_tagihan'];
$id_pelanggan = $_SESSION['pelanggan']['id_pelanggan'];
$metode       = $_POST['metode'];
$total_bayar  = $_POST['total_bayar'];
$biaya_admin  = 2000;
$bulan_bayar  = $_POST['bulan'];
$tanggal      = date("Y-m-d");

// Upload bukti
$namaFile = null;
if (!empty($_FILES['bukti']['name'])) {
    $namaFile = time() . "_" . $_FILES['bukti']['name'];
    move_uploaded_file($_FILES['bukti']['tmp_name'], "../../storages/bukti/" . $namaFile);
}

// Insert pembayaran pending
$q = "
INSERT INTO pembayaran 
(id_tagihan, id_pelanggan, tanggal_pembayaran, bulan_bayar, biaya_admin, total_bayar, metode, bukti, status, id_admin)
VALUES 
('$id_tagihan', '$id_pelanggan', '$tanggal', '$bulan_bayar', '$biaya_admin', '$total_bayar', '$metode', '$namaFile', 'Pending', 0)
";

if (mysqli_query($connect, $q)) {
    header("Location: pembayaran_selesai.php?status=success");
} else {
    echo "Error: " . mysqli_error($connect);
}
?>
