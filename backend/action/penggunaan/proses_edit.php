<?php
include '../../../config/connection.php';
include '../../../config/escapeString.php';

$id = $_POST['id'];
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];
$awal = $_POST['meter_awal'];
$akhir = $_POST['meter_ahir'];

$query = "
    UPDATE penggunaan SET 
        bulan = '$bulan',
        tahun = '$tahun',
        meter_awal = '$awal',
        meter_ahir = '$akhir'
    WHERE id_penggunaan = '$id'
";

mysqli_query($connect, $query);

header("Location: index.php?success=updated");
exit;
