<?php 
  // <= WAJIB
include '../partials/header.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: ../../backend/pages/auth/login.php");
    exit;
}

$id_pelanggan = $_SESSION['id_pelanggan'];

if (!$id_pelanggan) {
    die("ID pelanggan tidak ditemukan.");
}

// Query riwayat pembayaran pelanggan
$query = "
    SELECT 
        pb.id_pembayaran,
        pb.tanggal_pembayaran,
        pb.bulan_bayar,
        pb.metode,
        pb.status,
        pb.total_bayar,
        t.tahun,
        t.jumlah_meter
    FROM pembayaran pb
    LEFT JOIN tagihan t ON pb.id_tagihan = t.id_tagihan
    WHERE pb.id_pelanggan = '$id_pelanggan'
    ORDER BY pb.id_pembayaran DESC
";

$result = mysqli_query($connect, $query);
?>

<?php include '../partials/navbar.php'; ?>

<!-- Riwayat Pembayaran -->
<div class="container-xxl py-5">
  <div class="container py-5">

    <div class="text-center mb-5">
      <h6 class="text-secondary text-uppercase">Riwayat Pembayaran</h6>
      <h1 class="mb-4">Pembayaran Listrik Anda</h1>
      <p class="text-muted">Berikut adalah seluruh transaksi pembayaran yang sudah Anda lakukan.</p>
    </div>

    <div class="row g-4">

      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>

          <div class="col-lg-3 col-md-6">
            <div class="team-item p-4">

              <div class="overflow-hidden mb-4 text-center bg-light py-4 rounded">
                <h1 class="text-primary mb-0"><i class="bx bx-receipt"></i></h1>
              </div>

              <h5 class="mb-0">
                <?= htmlspecialchars($row['bulan_bayar']) . ' ' . htmlspecialchars($row['tahun']) ?>
              </h5>

              <p class="text-muted small mb-1">
                Total Bayar:
                <strong class="text-success">
                  Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?>
                </strong>
              </p>

              <p class="text-muted small mb-1">
                Metode: 
                <strong><?= htmlspecialchars($row['metode']) ?></strong>
              </p>

              <p class="small <?= $row['status'] == 'Lunas' ? 'text-success' : 'text-danger' ?>">
                Status: <strong><?= htmlspecialchars($row['status']) ?></strong>
              </p>

              <div class="btn-slide mt-1">
                <i class="fa fa-share"></i>
                <span>
                  <a href="detail_pembayaran.php?id=<?= $row['id_pembayaran'] ?>">
                    <i class="fa fa-eye"></i>
                  </a>
                </span>
              </div>

            </div>
          </div>

        <?php endwhile; ?>

      <?php else: ?>

        <div class="col-12 text-center">
          <div class="alert alert-warning">
            Belum ada riwayat pembayaran.
          </div>
        </div>

      <?php endif; ?>

    </div>

  </div>
</div>

<?php include '../partials/footer.php'; ?>
<?php include '../partials/script.php'; ?>
