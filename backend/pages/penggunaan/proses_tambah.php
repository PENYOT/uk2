<?php
include '../../../config/connection.php';

if (isset($_POST['simpan'])) {

    $id_pelanggan = $_POST['id_pelanggan'];
    $bulan        = $_POST['bulan'];
    $tahun        = $_POST['tahun'];
    $awal         = $_POST['meter_awal'];
    $akhir        = $_POST['meter_ahir'];
    $total_meter  = $akhir - $awal;

    // 1. Simpan ke tabel penggunaan
    $q1 = "
        INSERT INTO penggunaan (id_pelanggan, bulan, tahun, meter_awal, meter_ahir)
        VALUES ('$id_pelanggan', '$bulan', '$tahun', '$awal', '$akhir')
    ";

    if (mysqli_query($connect, $q1)) {

        // ambil id penggunaan terbaru
        $id_penggunaan = mysqli_insert_id($connect);

        // 2. Simpan tagihan otomatis
        $q2 = "
            INSERT INTO tagihan (id_penggunaan, id_pelanggan, bulan, tahun, jumlah_meter, status)
            VALUES ('$id_penggunaan', '$id_pelanggan', '$bulan', '$tahun', '$total_meter', 'Belum Bayar')
        ";
        mysqli_query($connect, $q2);

        // ambil id tagihan
        $id_tagihan = mysqli_insert_id($connect);

        // 3. Redirect ke halaman pembayaran frontend
        header("Location: index.php");
        exit;
    }

    header("Location: index.php?error=Gagal menambah data");
    exit;
}
?>
