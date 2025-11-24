<?php
include '../../../config/connection.php';

if (!isset($_GET['id'])) {
    echo "<script>
            alert('ID tagihan tidak ditemukan!');
            window.location.href = 'index.php';
          </script>";
    exit;
}

$id = $_GET['id'];

// Cek apakah data tagihan ada
$cek = mysqli_query($connect, "SELECT * FROM tagihan WHERE id_tagihan = '$id'");
if (mysqli_num_rows($cek) === 0) {
    echo "<script>
            alert('Data tagihan tidak ditemukan!');
            window.location.href = 'index.php';
          </script>";
    exit;
}

// Hapus data
$delete = mysqli_query($connect, "DELETE FROM tagihan WHERE id_tagihan = '$id'");

if ($delete) {
    echo "<script>
            alert('Tagihan berhasil dihapus!');
            window.location.href = 'index.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus data tagihan: " . mysqli_error($connect) . "');
            window.location.href = 'index.php';
          </script>";
}
