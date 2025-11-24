<?php
include '../../partials/header.php';
?>

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        <?php include '../../partials/sidebar.php'; ?>

        <div class="layout-page">
            <?php include '../../partials/navbar.php'; ?>

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">

                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="fw-bold mb-1 text-primary">
                                <i class="bx bx-group me-2"></i> Data Pelanggan
                            </h4>
                            <small class="text-muted">Daftar pelanggan dan status tagihan terakhir</small>
                        </div>
                    </div>

                    <!-- Card Container -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white fw-semibold">
                            <i class="bx bx-info-circle me-2"></i> Informasi Pelanggan
                        </div>

                        <div class="card-body bg-white">

                            <?php
                            $query = "
                SELECT 
                    p.*, 
                    t.daya, 
                    t.tarifperkwh,
                    (
                        SELECT tg.status 
                        FROM tagihan tg 
                        WHERE tg.id_pelanggan = p.id_pelanggan 
                        ORDER BY tg.id_tagihan DESC 
                        LIMIT 1
                    ) AS status_tagihan
                FROM pelanggan p
                LEFT JOIN tarif t ON p.id_tarif = t.id_tarif
                ORDER BY p.id_pelanggan ASC
              ";
                            $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
                            ?>

                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <div class="row row-cols-1 row-cols-md-2 g-4">
                                    <?php while ($row = mysqli_fetch_object($result)): ?>
                                        <div class="col">
                                            <div class="card border-light shadow-sm h-100 rounded-3">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <img src="../../../storages/pelanggan/<?= htmlspecialchars($row->image ?? 'default.png'); ?>"
                                                            alt="Foto"
                                                            class="rounded-circle shadow-sm me-3"
                                                            width="80" height="80"
                                                            style="object-fit: cover; border: 3px solid #f0f0f0;">
                                                        <div>
                                                            <h5 class="fw-semibold mb-1"><?= htmlspecialchars($row->nama_pelanggan); ?></h5>

                                                            <?php
                                                            $status = $row->status_tagihan ?? 'Tidak Ada';
                                                            $badgeClass = match ($status) {
                                                                'Lunas' => 'success',
                                                                'Belum Lunas' => 'danger',
                                                                default => 'secondary'
                                                            };
                                                            ?>
                                                            <span class="badge bg-<?= $badgeClass ?> px-3 py-2 mb-2">
                                                                <?= htmlspecialchars($status); ?>
                                                            </span>
                                                            <div class="text-muted small">
                                                                <i class="bx bx-id-card me-1"></i> <?= htmlspecialchars($row->nomor_kwh); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-borderless mb-2">
                                                            <tr>
                                                                <th class="text-muted" width="40%"><i class="bx bx-envelope me-2 text-primary"></i>Email</th>
                                                                <td><?= htmlspecialchars($row->email ?? '-'); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted"><i class="bx bx-user me-2 text-primary"></i>Username</th>
                                                                <td><?= htmlspecialchars($row->username ?? '-'); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted"><i class="bx bx-phone me-2 text-primary"></i>Telepon</th>
                                                                <td><?= htmlspecialchars($row->no_telepon ?? '-'); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted"><i class="bx bx-bulb me-2 text-primary"></i>Daya</th>
                                                                <td><?= htmlspecialchars($row->daya ?? '-'); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted"><i class="bx bx-money me-2 text-primary"></i>Tarif / KWh</th>
                                                                <td><?= isset($row->tarifperkwh) ? 'Rp ' . number_format($row->tarifperkwh, 2, ',', '.') : '-'; ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <div class="d-flex justify-content-end gap-2 mt-3">
                                                        <a href="../penggunaan/index.php?id_pelanggan=<?= $row->id_pelanggan; ?>" class="btn btn-warning btn-sm">
                                                            <i class="bx bx-bolt me-1"></i> Penggunaan
                                                        </a>
                                                        <a href="../tagihan/index.php?id_pelanggan=<?= $row->id_pelanggan; ?>"
                                                            class="btn btn-info btn-sm">
                                                            <i class="bx bx-file me-1"></i> Tagihan
                                                        </a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="bx bx-info-circle bx-lg d-block mb-3"></i>
                                    Tidak ada data pelanggan.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php include '../../partials/footer.php'; ?>
        </div>
    </div>
</div>

<?php include '../../partials/script.php'; ?>