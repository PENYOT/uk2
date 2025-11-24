<?php
include '../../../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $daya         = trim($_POST['daya']);
    $tarifperkwh  = trim($_POST['tarifperkwh']);

    if ($daya == "" || $tarifperkwh == "") {
        echo "<script>alert('Data tidak boleh kosong!'); history.back();</script>";
        exit;
    }

    $query = "INSERT INTO tarif (daya, tarifperkwh) VALUES ('$daya', '$tarifperkwh')";
    $insert = mysqli_query($connect, $query);

    if ($insert) {
        echo "<script>
                alert('Tarif berhasil ditambahkan!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan: " . mysqli_error($connect) . "');
                history.back();
              </script>";
    }
}
