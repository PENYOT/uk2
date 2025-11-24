<?php
// backend/pages/pelayanan/detail.php
include '../../partials/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) { header("Location:index.php"); exit; }

$q = "
    SELECT p.*, 
           a.username AS admin_pembuat, 
           b.username AS admin_update
    FROM pelayanan p
    LEFT JOIN admin a ON p.dibuat_oleh = a.id_admin
    LEFT JOIN admin b ON p.diupdate_oleh = b.id_admin
    WHERE p.id_pelayanan = $id
    LIMIT 1
";

$res = mysqli_query($connect, $q);
if (!mysqli_num_rows($res)) { header("Location:index.php"); exit; }
$row = mysqli_fetch_object($res);
?>

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        <?php include '../../partials/sidebar.php'; ?>
        <div class="layout-page">

            <?php include '../../partials/navbar.php'; ?>

            <div class="content-wrapper">
                <div class="container-xxl p-4">

                    <!-- TOMBOL KEMBALI -->
                    <div class="mb-3">
                        <a href="index.php" class="btn btn-outline-primary shadow-sm">
                            <i class="bx bx-arrow-back"></i> Kembali
                        </a>
                    </div>

                    <!-- CARD DETAIL -->
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                        <!-- Header -->
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="bx bx-detail fs-4 me-2"></i> Detail Pelayanan
                            </h5>
                        </div>

                        <!-- Body -->
                        <div class="card-body p-4">

                            <div class="row g-4">
                                <!-- GAMBAR -->
                                <div class="col-md-4 text-center">
                                    <img src="../../../storages/layanan/<?= htmlspecialchars($row->gambar ?: 'default.jpg') ?>"
                                         class="rounded-4 shadow-sm w-100"
                                         style="height:250px; object-fit:cover;"
                                         alt="Gambar Pelayanan">
                                </div>

                                <!-- INFORMASI -->
                                <div class="col-md-8">
                                    <h3 class="fw-bold mb-2"><?= htmlspecialchars($row->judul) ?></h3>

                                    <p class="mb-2">
                                        <span class="badge bg-<?= $row->status == 'Aktif' ? 'success' : 'secondary' ?> px-3 py-2">
                                            <i class="bx bx-check-circle"></i> <?= htmlspecialchars($row->status) ?>
                                        </span>
                                    </p>

                                    <div class="mt-3 mb-4">
                                        <h6 class="fw-bold text-secondary">Deskripsi:</h6>
                                        <p class="text-dark" style="white-space:pre-line;"><?= htmlspecialchars($row->deskripsi) ?></p>
                                    </div>

                                    <hr class="my-3">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1 text-muted small">Dibuat oleh:</p>
                                            <p class="fw-semibold"><?= htmlspecialchars($row->admin_pembuat ?? '-') ?></p>
                                            <p class="text-muted small"><?= htmlspecialchars($row->dibuat_at) ?></p>
                                        </div>

                                        <div class="col-md-6">
                                            <p class="mb-1 text-muted small">Diupdate oleh:</p>
                                            <p class="fw-semibold"><?= htmlspecialchars($row->admin_update ?? '-') ?></p>
                                            <p class="text-muted small"><?= htmlspecialchars($row->diupdate_at) ?></p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <!-- Footer -->
                        <div class="card-footer bg-light py-3 text-end">
                            <a href="edit.php?id=<?= $row->id_pelayanan ?>" class="btn btn-warning text-white shadow-sm">
                                <i class="bx bx-edit"></i> Edit
                            </a>
                        </div>

                    </div> <!-- end card -->

                </div>
            </div>

            <?php include '../../partials/footer.php'; ?>
        </div>

    </div>
</div>

<?php include '../../partials/script.php'; ?>
