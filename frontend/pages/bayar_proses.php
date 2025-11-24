<?php
session_start();
include '../../config/connection.php';

$id_pelanggan = $_SESSION['pelanggan']['id'];
$id_tagihan   = $_POST['id_tagihan'] ?? null;
$total_bayar  = $_POST['total_bayar'] ?? 0;

if (!$id_tagihan) {
    die("ID Tagihan tidak ditemukan!");
}

// Upload bukti
$folder = "../../storages/bukti_pembayaran/";
if (!is_dir($folder)) mkdir($folder, 0777, true);

$namaFile = time() . "_" . $_FILES['bukti']['name'];
$path = $folder . $namaFile;

move_uploaded_file($_FILES['bukti']['tmp_name'], $path);

// Simpan ke tabel pembayaran
mysqli_query($connect, "
    INSERT INTO pembayaran (id_tagihan, id_pelanggan, total_bayar, bukti, status)
    VALUES ('$id_tagihan', '$id_pelanggan', '$total_bayar', '$namaFile', 'Menunggu Verifikasi')
");

// Update status tagihan
mysqli_query($connect, "
    UPDATE tagihan SET status = 'Menunggu Verifikasi' WHERE id_tagihan = '$id_tagihan'
");

header("Location: pembayaran.php");
exit;
?>
