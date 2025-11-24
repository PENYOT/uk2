<?php
include '../../../config/connection.php';

if (!isset($_GET['id_tagihan'])) {
    die("ID Tagihan tidak valid!");
}

$id_tagihan = intval($_GET['id_tagihan']);

// Ambil data pelanggan berdasarkan id_tagihan
$q = mysqli_query($connect, "
    SELECT p.id_pelanggan, p.username, p.email 
    FROM tagihan t
    LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    WHERE t.id_tagihan = $id_tagihan
");

$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Tagihan tidak ditemukan!");
}

// URL tujuan pembayaran frontend
$paymentURL = "../../../frontend/pages/pembayaran?id_tagihan=" . $id_tagihan;

// NOTE: Anda bisa menambahkan kirim email atau notifikasi WA di sini jika ingin

// Redirect ke halaman pembayaran frontend
header("Location: $paymentURL");
exit;
?>
