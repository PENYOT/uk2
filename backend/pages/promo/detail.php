<?php
include '../../partials/header.php';
include '../../../config/connection.php';

$id = intval($_GET['id']);
$q = "
    SELECT p.*, a.username AS admin_pembuat, b.username AS admin_update
    FROM promo p
    LEFT JOIN admin a ON p.dibuat_oleh=a.id_admin
    LEFT JOIN admin b ON p.diupdate_oleh=b.id_admin
    WHERE id_promo=$id LIMIT 1
";

$res = mysqli_query($connect, $q);
if (!mysqli_num_rows($res)) { header("Location:index.php"); exit; }
$promo = mysqli_fetch_object($res);
?>

<style>
.btn-back-custom {
    background: #f8f9fa;
    border: 1px solid #d3d3d3;
    padding: 8px 16px;
    border-radius: 10px;
    font-weight: 600;
    transition: 0.2s;
}
.btn-back-custom:hover {
    background: #e9ecef;
}
</style>

<div class="layout-wrapper layout-content-navbar">
<div class="layout-container">

<?php include '../../partials/sidebar.php'; ?>

<div class="layout-page">
<?php include '../../partials/navbar.php'; ?>

<div class="content-wrapper">
<div class="container-xxl p-4">

    <!-- 🔙 TOMBOL KEMBALI -->
    <div class="mb-3 d-flex gap-2">
        <a href="index.php" class="btn btn-back-custom">
            <i class="bx bx-left-arrow-alt"></i> Kembali
        </a>

        <a href="edit.php?id=<?= $promo->id_promo ?>" class="btn btn-warning text-white">
            <i class="bx bx-edit"></i> Edit
        </a>

        <a onclick="return confirm('Yakin ingin menghapus promo ini?')"
           href="hapus.php?id=<?= $promo->id_promo ?>"
           class="btn btn-danger">
            <i class="bx bx-trash"></i> Hapus
        </a>
    </div>

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">

            <div class="row">
                <!-- GAMBAR PROMO -->
                <div class="col-md-4 text-center">
                    <img src="../../../storages/promo/<?= $promo->gambar ?>"
                         class="rounded shadow-sm mb-3"
                         style="width:100%;height:220px;object-fit:cover">

                    <span class="badge bg-<?= $promo->status=='Aktif'?'success':'secondary' ?> px-3 py-2">
                        <?= $promo->status ?>
                    </span>
                </div>

                <!-- DETAIL PROMO -->
                <div class="col-md-8">
                    <h3 class="fw-bold"><?= htmlspecialchars($promo->judul) ?></h3>

                    <p class="text-muted"><?= nl2br(htmlspecialchars($promo->deskripsi)) ?></p>

                    <hr>

                    <p class="fw-semibold mb-1">Diskon:</p>
                    <p><span class="badge bg-info"><?= $promo->diskon ?>%</span></p>

                    <p class="fw-semibold mb-1">Periode Promo:</p>
                    <p>
                        <strong><?= $promo->tanggal_mulai ?></strong>
                        <span class="text-muted">→</span>
                        <strong><?= $promo->tanggal_selesai ?></strong>
                    </p>

                    <hr>

                    <p><small>
                        Dibuat oleh: <strong><?= $promo->admin_pembuat ?? '-' ?></strong> 
                        pada <?= $promo->dibuat_at ?>
                    </small></p>

                    <p><small>
                        Diperbarui oleh: <strong><?= $promo->admin_update ?? '-' ?></strong> 
                        pada <?= $promo->diupdate_at ?>
                    </small></p>

                </div>
            </div>

        </div>
    </div>

</div>
</div>

<?php include '../../partials/footer.php'; ?>
</div>
</div>
</div>

<?php include '../../partials/script.php'; ?>
