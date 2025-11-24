<?php
// backend/pages/pelayanan/hapus.php
include '../../../config/connection.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) { header("Location: index.php"); exit; }

// ambil nama file gambar untuk dihapus
$q = "SELECT gambar FROM pelayanan WHERE id_pelayanan = $id LIMIT 1";
$r = mysqli_query($connect, $q);
if ($r && mysqli_num_rows($r)) {
    $row = mysqli_fetch_assoc($r);
    $g = $row['gambar'];
    if ($g && $g !== 'default.jpg' && file_exists('../../../storages/layanan/'.$g)) {
        @unlink('../../../storages/layanan/'.$g);
    }
}

// hapus record
$del = "DELETE FROM pelayanan WHERE id_pelayanan = $id";
if (mysqli_query($connect, $del)) {
    header("Location: index.php?success=deleted");
} else {
    header("Location: index.php?error=delete_failed");
}
exit;
