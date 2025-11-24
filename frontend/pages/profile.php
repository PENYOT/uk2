<?php
session_start();
include '../partials/header.php';

// Wajib login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_SESSION['id_pelanggan'];

$qPelanggan = mysqli_query($connect, "
    SELECT p.*, t.daya, t.tarifperkwh
    FROM pelanggan p
    LEFT JOIN tarif t ON p.id_tarif = t.id_tarif
    WHERE p.id_pelanggan = $id
");

$pelanggan = mysqli_fetch_object($qPelanggan);

$q = mysqli_query($connect, $sql);

if (!$q) {
    die("SQL ERROR: " . mysqli_error($connect));
}

if (!$pelanggan) {
    die("<h3>Data pelanggan tidak ditemukan!</h3>");
}

// Hitung tagihan belum dibayar
$qBelum = mysqli_query($connect, "
    SELECT COUNT(*) AS total 
    FROM tagihan 
    WHERE id_pelanggan = $id AND status = 'Belum Bayar'
");
$totalBelum = mysqli_fetch_object($qBelum)->total ?? 0;

// Hitung tagihan lunas
$qLunas = mysqli_query($connect, "
    SELECT COUNT(*) AS total 
    FROM pembayaran 
    WHERE id_pelanggan = $id AND status = 'Lunas'
");
$totalLunas = mysqli_fetch_object($qLunas)->total ?? 0;

include '../partials/navbar.php';
?>

<style>
    .profile-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 5px 20px rgba(0,0,0,.15);
    }

    .stat-card {
        transition: .2s;
        border-radius: 14px;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,.15);
    }

    .info-label {
        font-weight: 600;
        color: #555;
        width: 140px;
    }
</style>

<div class="container py-5">

    <h2 class="fw-bold mb-4">Profil Pelanggan</h2>

    <div class="row g-4">

        <!-- PROFIL KIRI -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 text-center p-4">

                <img src="../../storages/pelanggan/<?= $pelanggan->image ?: 'default.jpg' ?>"
                     class="profile-img mb-3">

                <h4 class="fw-bold"><?= $pelanggan['nama_pelanggan'] ?></h4>
                <p class="text-muted mb-1"><?= $pelanggan->email ?></p>
                <p class="text-muted">No. KWH: <?= $pelanggan->nomor_kwh ?></p>

                <hr>

                <p><strong>Telepon:</strong><br><?= $pelanggan->phone ?></p>

                <p><strong>Alamat:</strong><br><?= $pelanggan->alamat ?></p>

                <p><strong>Daya Listrik:</strong><br>
                    <?= $pelanggan->daya ?: '-' ?>  
                    (Rp <?= number_format($pelanggan->tarifperkwh ?: 0, 0, ',', '.') ?>/kWh)
                </p>
            </div>
        </div>

        <!-- KANAN -->
        <div class="col-lg-8">
            <div class="row g-4">

                <!-- BELUM BAYAR -->
                <div class="col-md-6">
                    <div class="stat-card shadow-sm p-4 border-0 bg-light">
                        <h6 class="text-muted">Tagihan Belum Dibayar</h6>
                        <h2 class="fw-bold text-danger"><?= $totalBelum ?></h2>
                    </div>
                </div>

                <!-- SUDAH LUNAS -->
                <div class="col-md-6">
                    <div class="stat-card shadow-sm p-4 border-0 bg-light">
                        <h6 class="text-muted">Tagihan Lunas</h6>
                        <h2 class="fw-bold text-success"><?= $totalLunas ?></h2>
                    </div>
                </div>

                <!-- INFORMASI AKUN -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 p-4">

                        <h5 class="fw-bold mb-3">Informasi Akun</h5>

                        <div class="d-flex mb-2">
                            <div class="info-label">Nama</div>
                            <div><?= $pelanggan->nama_pelanggan ?></div>
                        </div>

                        <div class="d-flex mb-2">
                            <div class="info-label">Email</div>
                            <div><?= $pelanggan->email ?></div>
                        </div>

                        <div class="d-flex mb-2">
                            <div class="info-label">Nomor KWH</div>
                            <div><?= $pelanggan->nomor_kwh ?></div>
                        </div>

                        <div class="d-flex mb-2">
                            <div class="info-label">Telepon</div>
                            <div><?= $pelanggan->phone ?></div>
                        </div>

                        <div class="d-flex mb-2">
                            <div class="info-label">Alamat</div>
                            <div><?= $pelanggan->alamat ?></div>
                        </div>

                        <div class="d-flex mb-2">
                            <div class="info-label">Daya Listrik</div>
                            <div>
                                <?= $pelanggan->daya ?: '-' ?>  
                                (Rp <?= number_format($pelanggan->tarifperkwh ?: 0, 0, ',', '.') ?>/kWh)
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<?php include '../partials/footer.php'; ?>
<?php include '../partials/script.php'; ?>
