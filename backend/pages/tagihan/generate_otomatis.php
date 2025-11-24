<?php
include '../../../config/connection.php';

// Ambil semua pelanggan
$qPelanggan = mysqli_query($connect, "SELECT id_pelanggan FROM pelanggan");

$bulan = date('F');
$tahun = date('Y');

while ($p = mysqli_fetch_assoc($qPelanggan)) {

    $id_pelanggan = $p['id_pelanggan'];

    // Cek apakah tagihan bulan ini sudah ada
    $cek = mysqli_query($connect, "
        SELECT 1 FROM tagihan 
        WHERE id_pelanggan = '$id_pelanggan'
        AND bulan = '$bulan'
        AND tahun = '$tahun'
    ");

    if (mysqli_num_rows($cek) == 0) {

        // Insert tagihan otomatis
        mysqli_query($connect, "
            INSERT INTO tagihan (id_penggunaan, id_pelanggan, bulan, tahun, jumlah_meter, status)
            VALUES (NULL, '$id_pelanggan', '$bulan', '$tahun', 0, 'Belum Bayar')
        ");
    }
}

header("Location: index.php?success=1");
exit;
?>
