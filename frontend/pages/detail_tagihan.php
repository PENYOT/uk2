<?php
// session_start();
include '../partials/header.php';
include '../partials/navbar.php';

// CEK LOGIN
// if (!isset($_SESSION['id_pelanggan'])) { // Menggunakan variabel sesi dari navbar
//     echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='../../backend/pages/auth/login.php';</script>";
//     exit;
// }

// Gunakan id_pelanggan untuk query, asumsikan id_pelanggan sudah diset di sesi
$id_login = $_SESSION['id_pelanggan'];

// Tambahan: Pastikan semua data yang dibutuhkan sudah diset saat login (name, email, dll.)
// Jika proses login Anda hanya menyetel id_pelanggan, ini sudah cukup. 
// Jika Anda perlu data lain, Anda harus mengambilnya dari database atau menyetelnya saat login.

// ID tagihan dari URL
$id_tagihan = $_GET['id_tagihan'] ?? null;

if (!$id_tagihan) {
    $cekTagihan = mysqli_query($connect, "
        SELECT id_tagihan 
        FROM tagihan 
        WHERE id_pelanggan = '$id_login' 
        AND status = 'Belum Bayar'
        LIMIT 1
    ");

    $row = mysqli_fetch_assoc($cekTagihan);

    if ($row) {
        $id_tagihan = $row['id_tagihan'];

        // redirect dengan id_tagihan agar aman
        header("Location: detail_tagihan.php?id_tagihan=" . $id_tagihan);
        exit;
    } else {
        echo "<script>
            alert('Anda tidak memiliki tagihan yang perlu dibayar.');
            window.location.href = 'index.php';
        </script>";
        exit;
    }
}

// Query detail tagihan
$query = "
    SELECT 
        t.id_tagihan,
        t.bulan,
        t.tahun,
        t.jumlah_meter,
        t.id_pelanggan,
        p.nama_pelanggan,
        p.nomor_kwh,
        p.alamat,
        tr.daya,
        tr.tarifperkwh
    FROM tagihan t
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    JOIN tarif tr ON p.id_tarif = tr.id_tarif
    WHERE t.id_tagihan = '$id_tagihan'
    LIMIT 1
";

$result = mysqli_query($connect, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div class='alert alert-danger text-center mt-5'>Tagihan tidak ditemukan</div>";
    exit;
}

if ($data['id_pelanggan'] != $id_login) {
    echo "<script>alert('Anda tidak memiliki akses ke tagihan ini!'); window.location.href='../index.php';</script>";
    exit;
}
?>

<div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="bi bi-receipt"></i> Detail Tagihan Listrik</h4>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 shadow-sm">
                        <h6 class="text-primary fw-bold mb-3">Informasi Pelanggan</h6>
                        <p><strong>Nama:</strong><br><?= $data['nama_pelanggan']; ?></p>
                        <p><strong>Nomor KWH:</strong><br><?= $data['nomor_kwh']; ?></p>
                        <p><strong>Alamat:</strong><br><?= $data['alamat']; ?></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 shadow-sm">
                        <h6 class="text-primary fw-bold mb-3">Detail Tagihan</h6>
                        <p><strong>Periode:</strong><br><?= $data['bulan']; ?> <?= $data['tahun']; ?></p>
                        <p><strong>Pemakaian:</strong><br><?= $data['jumlah_meter']; ?> kWh</p>
                        <p><strong>Tarif/kWh:</strong><br>Rp <?= number_format($data['tarifperkwh']); ?></p>
                    </div>
                </div>

            </div>

            <div class="mt-4 p-4 bg-white border rounded-3 shadow-sm text-center">
                <h4 class="text-success fw-bold">Total Bayar :
                    Rp <?= number_format(($data['jumlah_meter'] * $data['tarifperkwh']) + 2000, 0, ',', '.'); ?>
                </h4>
                <small class="text-muted">* Termasuk biaya admin Rp 2.000</small>
            </div>

        </div>

        <div class="card-footer text-center">
            <a href="pembayaran.php?id_tagihan=<?= $data['id_tagihan']; ?>" class="btn btn-success px-4 py-2 fw-bold">
                Bayar Sekarang
            </a>
        </div>

    </div>
</div>
