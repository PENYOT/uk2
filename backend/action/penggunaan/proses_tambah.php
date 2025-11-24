<?php
include '../../../config/connection.php';
include '../../../config/escapeString.php';

$id_pelanggan = $_POST['id_pelanggan'];
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];
$awal = $_POST['meter_awal'];
$akhir = $_POST['meter_ahir'];

$query = "INSERT INTO penggunaan (id_pelanggan, bulan, tahun, meter_awal, meter_ahir)
          VALUES ('$id_pelanggan', '$bulan', '$tahun', '$awal', '$akhir')";
mysqli_query($connect, $query);

header("Location: index.php?success=added");
exit;
