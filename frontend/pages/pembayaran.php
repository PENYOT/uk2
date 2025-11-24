<?php
include '../partials/header.php';
include '../partials/navbar.php';

// if (!isset($_SESSION['pelanggan'])) {
//     echo "<script>alert('Silakan login!'); window.location.href='../../backend/pages/auth/login.php';</script>";
//     exit;
// }

$id_pelanggan = $_SESSION['id_pelanggan'];

// ID tagihan dari URL
$idTagihan = $_GET['id_tagihan'] ?? null;

if (!$idTagihan) {
    die("<div class='alert alert-danger text-center mt-5'>Tagihan tidak valid!</div>");
}

// Ambil data tagihan
$q = mysqli_query($connect, "
    SELECT 
        t.*, 
        p.nama_pelanggan,
        tr.tarifperkwh
    FROM tagihan t
    JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
    JOIN tarif tr ON tr.id_tarif = p.id_tarif
    WHERE t.id_tagihan = '$idTagihan'
    AND t.id_pelanggan = '$id_pelanggan'
");

$tagihan = mysqli_fetch_assoc($q);

if (!$tagihan) {
    die("<div class='alert alert-danger text-center mt-5'>Tagihan tidak ditemukan atau bukan milik Anda.</div>");
}

$biaya_admin = 2000;
$total_bayar = ($tagihan['jumlah_meter'] * $tagihan['tarifperkwh']) + $biaya_admin;

// Submit pembayaran
if (isset($_POST['bayar'])) {

    $metode = $_POST['metode'];

    // Upload bukti
    $buktiName = null;
    if (!empty($_FILES['bukti']['name'])) {
        $buktiName = time() . "_" . $_FILES['bukti']['name'];
        move_uploaded_file($_FILES['bukti']['tmp_name'], "../../assets/bukti/" . $buktiName);
    }

    mysqli_query($connect, "
        INSERT INTO pembayaran 
(id_tagihan, id_pelanggan, tanggal_pembayaran, bulan_bayar, biaya_admin, total_bayar, metode, bukti, status, id_admin)
VALUES 
(
    '$idTagihan',
    '$id_pelanggan',
    CURDATE(),
    '{$tagihan['bulan']} {$tagihan['tahun']}',
    '$biaya_admin',
    '$total_bayar',
    '$metode',
    '$buktiName',
    'Menunggu Verifikasi',
    NULL
)

    ");

    mysqli_query($connect, "
        UPDATE tagihan SET status='Menunggu Verifikasi'
        WHERE id_tagihan='$idTagihan'
    ");

    echo "<script>alert('Pembayaran berhasil dikirim!'); window.location.href='index.php';</script>";
    exit;
}
?>

<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white py-3">
            <h4 class="mb-0"><i class="bi bi-wallet"></i> Pembayaran Tagihan</h4>
        </div>

        <div class="card-body p-4">
            <p><strong>Nama:</strong> <?= $tagihan['nama_pelanggan']; ?></p>
            <p><strong>Total yang harus dibayar:</strong> 
                <span class="text-success fw-bold">Rp <?= number_format($total_bayar, 0, ',', '.'); ?></span>
            </p>

            <form method="POST" enctype="multipart/form-data">
                <label class="fw-bold">Metode Pembayaran</label>
                <select name="metode" class="form-control mb-3" required>
                    <option value="">-- Pilih --</option>
                    <option>Transfer Bank</option>
                    <option>E-Wallet (Dana, OVO, Gopay)</option>
                </select>

                <label class="fw-bold">Upload Bukti Pembayaran</label>
                <input type="file" name="bukti" class="form-control mb-3" required>

                <button type="submit" name="bayar" class="btn btn-success w-100 py-2 fw-bold">
                    KIRIM PEMBAYARAN
                </button>
            </form>
        </div>
    </div>
</div>
