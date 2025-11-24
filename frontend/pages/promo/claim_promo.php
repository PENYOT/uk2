<?php
// session_start();
include '../../../config/connection.php';

// Pastikan ada ID promo
if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$id_promo = intval($_GET['id']);

// Pastikan user sudah login
// if (!isset($_SESSION['pelanggan']['id_pelanggan'])) {
//     header("Location: ../auth/login.php?msg=Silahkan login untuk claim promo");
//     exit;
// }

$id_pelanggan = $_SESSION['id_pelanggan'];

// Ambil data promo
$qPromo = "
    SELECT * FROM promo 
    WHERE id_promo = $id_promo 
    AND status = 'Aktif'
    LIMIT 1
";

$resPromo = mysqli_query($connect, $qPromo);

if (!mysqli_num_rows($resPromo)) {
    echo "<script>alert('Promo tidak ditemukan atau sudah tidak aktif!'); window.history.back();</script>";
    exit;
}

$promo = mysqli_fetch_object($resPromo);

// Simpan promo ke SESSION untuk digunakan saat pembayaran
$_SESSION['promo'] = [
    'id_promo' => $promo->id_promo,
    'judul'    => $promo->judul,
    'diskon'   => $promo->diskon,
    'mulai'    => $promo->tanggal_mulai,
    'selesai'  => $promo->tanggal_selesai
];

// Redirect ke halaman pembayaran
header("Location: ../pembayaran.php?promo=success");
exit;
?>
