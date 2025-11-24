<?php
// action/hapus.php
// Robust universal delete with whitelist + transaction
include '../../config/connection.php';

// Accept GET or POST
$method = $_SERVER['REQUEST_METHOD'];
$params = $method === 'POST' ? $_POST : $_GET;

$table = $params['table'] ?? null;
$id    = $params['id'] ?? null;

// Basic checks
if (!$table || !$id) {
    echo "<script>alert('Parameter tidak lengkap. Pastikan Anda memanggil hapus.php?table=...&id=... atau mengirim via POST.'); window.history.back();</script>";
    exit;
}

// whitelist tabel yang boleh dihapus lewat script ini
$whitelist = [
    'pelanggan','tagihan','penggunaan','tarif','pembayaran',
    'promo','admin','level','pelayanan'
];

if (!in_array($table, $whitelist)) {
    echo "<script>alert('Tabel tidak diizinkan untuk penghapusan.'); window.history.back();</script>";
    exit;
}

// sanitize id as integer
$id = intval($id);

mysqli_begin_transaction($connect);

try {

    // handle special cascading deletes per table
    if ($table === 'pelanggan') {
        // delete pembayaran -> tagihan -> penggunaan -> pelanggan
        $q = "SELECT id_tagihan FROM tagihan WHERE id_pelanggan = ?";
        $stmt = mysqli_prepare($connect, $q);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($r = mysqli_fetch_assoc($res)) {
            $id_tagihan = intval($r['id_tagihan']);
            // delete pembayaran for that tagihan
            $d = mysqli_prepare($connect, "DELETE FROM pembayaran WHERE id_tagihan = ?");
            mysqli_stmt_bind_param($d, "i", $id_tagihan);
            mysqli_stmt_execute($d);
        }
        // delete tagihan
        $delTag = mysqli_prepare($connect, "DELETE FROM tagihan WHERE id_pelanggan = ?");
        mysqli_stmt_bind_param($delTag, "i", $id);
        mysqli_stmt_execute($delTag);

        // delete penggunaan
        $delPeng = mysqli_prepare($connect, "DELETE FROM penggunaan WHERE id_pelanggan = ?");
        mysqli_stmt_bind_param($delPeng, "i", $id);
        mysqli_stmt_execute($delPeng);

        // finally delete pelanggan
        $delPel = mysqli_prepare($connect, "DELETE FROM pelanggan WHERE id_pelanggan = ?");
        mysqli_stmt_bind_param($delPel, "i", $id);
        mysqli_stmt_execute($delPel);
    }

    elseif ($table === 'tagihan') {
        // delete pembayaran related then tagihan
        $delPay = mysqli_prepare($connect, "DELETE FROM pembayaran WHERE id_tagihan = ?");
        mysqli_stmt_bind_param($delPay, "i", $id);
        mysqli_stmt_execute($delPay);

        $delTag = mysqli_prepare($connect, "DELETE FROM tagihan WHERE id_tagihan = ?");
        mysqli_stmt_bind_param($delTag, "i", $id);
        mysqli_stmt_execute($delTag);
    }

    elseif ($table === 'penggunaan') {
        // find tagihan related to penggunaan
        $q = "SELECT id_tagihan FROM tagihan WHERE id_penggunaan = ?";
        $stmt = mysqli_prepare($connect, $q);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($r = mysqli_fetch_assoc($res)) {
            $tagId = intval($r['id_tagihan']);
            $d = mysqli_prepare($connect, "DELETE FROM pembayaran WHERE id_tagihan = ?");
            mysqli_stmt_bind_param($d, "i", $tagId);
            mysqli_stmt_execute($d);
        }
        $delTag = mysqli_prepare($connect, "DELETE FROM tagihan WHERE id_penggunaan = ?");
        mysqli_stmt_bind_param($delTag, "i", $id);
        mysqli_stmt_execute($delTag);

        $delPeng = mysqli_prepare($connect, "DELETE FROM penggunaan WHERE id_penggunaan = ?");
        mysqli_stmt_bind_param($delPeng, "i", $id);
        mysqli_stmt_execute($delPeng);
    }

    elseif ($table === 'tarif') {
        // delete all customers who use this tarif (and their cascades)
        $q = "SELECT id_pelanggan FROM pelanggan WHERE id_tarif = ?";
        $stmt = mysqli_prepare($connect, $q);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($p = mysqli_fetch_assoc($res)) {
            $pelId = intval($p['id_pelanggan']);
            // re-use pelanggan deletion logic
            // delete pembayaran -> tagihan -> penggunaan -> pelanggan
            // delete pembayaran for tagihans
            $q2 = "SELECT id_tagihan FROM tagihan WHERE id_pelanggan = ?";
            $s2 = mysqli_prepare($connect, $q2);
            mysqli_stmt_bind_param($s2, "i", $pelId);
            mysqli_stmt_execute($s2);
            $r2 = mysqli_stmt_get_result($s2);
            while ($r3 = mysqli_fetch_assoc($r2)) {
                $id_tagihan = intval($r3['id_tagihan']);
                $d = mysqli_prepare($connect, "DELETE FROM pembayaran WHERE id_tagihan = ?");
                mysqli_stmt_bind_param($d, "i", $id_tagihan);
                mysqli_stmt_execute($d);
            }
            mysqli_query($connect, "DELETE FROM tagihan WHERE id_pelanggan = '$pelId'");
            mysqli_query($connect, "DELETE FROM penggunaan WHERE id_pelanggan = '$pelId'");
            mysqli_query($connect, "DELETE FROM pelanggan WHERE id_pelanggan = '$pelId'");
        }

        // finally delete tarif row
        $delTar = mysqli_prepare($connect, "DELETE FROM tarif WHERE id_tarif = ?");
        mysqli_stmt_bind_param($delTar, "i", $id);
        mysqli_stmt_execute($delTar);
    }

    elseif ($table === 'pelayanan' || $table === 'layanan') {
        // delete customers under this service
        $q = "SELECT id_pelanggan FROM pelanggan WHERE id_layanan = ?";
        $stmt = mysqli_prepare($connect, $q);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($p = mysqli_fetch_assoc($res)) {
            $pelId = intval($p['id_pelanggan']);
            // cascade delete pelanggan (same as above)
            $q2 = "SELECT id_tagihan FROM tagihan WHERE id_pelanggan = ?";
            $s2 = mysqli_prepare($connect, $q2);
            mysqli_stmt_bind_param($s2, "i", $pelId);
            mysqli_stmt_execute($s2);
            $r2 = mysqli_stmt_get_result($s2);
            while ($r3 = mysqli_fetch_assoc($r2)) {
                $id_tagihan = intval($r3['id_tagihan']);
                $d = mysqli_prepare($connect, "DELETE FROM pembayaran WHERE id_tagihan = ?");
                mysqli_stmt_bind_param($d, "i", $id_tagihan);
                mysqli_stmt_execute($d);
            }
            mysqli_query($connect, "DELETE FROM tagihan WHERE id_pelanggan = '$pelId'");
            mysqli_query($connect, "DELETE FROM penggunaan WHERE id_pelanggan = '$pelId'");
            mysqli_query($connect, "DELETE FROM pelanggan WHERE id_pelanggan = '$pelId'");
        }
        $del = mysqli_prepare($connect, "DELETE FROM pelayanan WHERE id_pelayanan = ?");
        mysqli_stmt_bind_param($del, "i", $id);
        mysqli_stmt_execute($del);
    }

    elseif ($table === 'pembayaran') {
        $del = mysqli_prepare($connect, "DELETE FROM pembayaran WHERE id_pembayaran = ?");
        mysqli_stmt_bind_param($del, "i", $id);
        mysqli_stmt_execute($del);
    }

    elseif ($table === 'promo') {
        $del = mysqli_prepare($connect, "DELETE FROM promo WHERE id_promo = ?");
        mysqli_stmt_bind_param($del, "i", $id);
        mysqli_stmt_execute($del);
    }

    elseif ($table === 'admin') {
        $del = mysqli_prepare($connect, "DELETE FROM admin WHERE id_admin = ?");
        mysqli_stmt_bind_param($del, "i", $id);
        mysqli_stmt_execute($del);
    }

    elseif ($table === 'level') {
        $del = mysqli_prepare($connect, "DELETE FROM level WHERE id_level = ?");
        mysqli_stmt_bind_param($del, "i", $id);
        mysqli_stmt_execute($del);
    }

    // commit
    mysqli_commit($connect);

    echo "<script>alert('Data dan relasi berhasil dihapus.'); window.location.href = '../pages/dashboard/index.php';</script>";
    exit;
}
catch (Exception $e) {
    mysqli_rollback($connect);
    echo "<script>alert('Gagal menghapus data: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    exit;
}
