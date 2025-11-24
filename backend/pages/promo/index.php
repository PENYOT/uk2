<?php
include '../../partials/header.php';

// Ambil data promo
$q = "
    SELECT p.*, 
           a.username AS admin_pembuat, 
           b.username AS admin_update
    FROM promo p
    LEFT JOIN admin a ON p.dibuat_oleh = a.id_admin
    LEFT JOIN admin b ON p.diupdate_oleh = b.id_admin
    ORDER BY p.id_promo DESC
";
$result = mysqli_query($connect, $q);
?>

<style>
    .promo-img {
        width: 90px;
        height: 65px;
        object-fit: cover;
        border-radius: 10px;
    }

    .table thead th {
        vertical-align: middle;
    }

    .badge {
        font-size: 12px;
    }
</style>

<!-- DATATABLES CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        <?php include '../../partials/sidebar.php'; ?>
        <div class="layout-page">
            <?php include '../../partials/navbar.php'; ?>

            <div class="content-wrapper">
                <div class="container-xxl p-4">

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="fw-bold text-primary">
                                <i class="bx bx-purchase-tag-alt me-2"></i> Manajemen Promo
                            </h4>
                            <small class="text-muted">Kelola daftar promo menarik untuk pelanggan.</small>
                        </div>
                        <a href="tambah.php" class="btn btn-primary shadow-sm">
                            <i class="bx bx-plus"></i> Tambah Promo
                        </a>
                    </div>

                    <!-- TABLE -->
                    <div class="card border-0 shadow-lg rounded-4">
                        <div class="card-header bg-primary text-white fw-semibold">
                            <i class="bx bx-list-ul me-2"></i> Daftar Promo
                        </div>

                        <div class="card-body">

                            <table id="promoTable" class="table table-hover align-middle table-bordered display nowrap" style="width:100%">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Judul</th>
                                        <th>Diskon</th>
                                        <th>Status</th>
                                        <th>Periode</th>
                                        <th>Dibuat Oleh</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (mysqli_num_rows($result)):
                                        $no = 1;
                                        while ($row = mysqli_fetch_object($result)): ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>

                                                <td class="text-center">
                                                    <img src="../../../storages/promo/<?= $row->gambar ?>" class="promo-img shadow-sm">
                                                </td>

                                                <td><span class="text-limit"><?= htmlspecialchars($row->judul) ?></span></td>

                                                <td class="text-center">
                                                    <span class="badge bg-info"><?= $row->diskon ?>%</span>
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge bg-<?= $row->status == 'Aktif' ? 'success' : 'secondary' ?>">
                                                        <?= $row->status ?>
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    <?= $row->tanggal_mulai ?> <br>
                                                    <small class="text-muted">s/d</small> <br>
                                                    <?= $row->tanggal_selesai ?>
                                                </td>

                                                <td><?= $row->admin_pembuat ?></td>

                                                <td class="text-center">
                                                    <a href="detail.php?id=<?= $row->id_promo ?>" class="btn btn-info btn-sm">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="edit.php?id=<?= $row->id_promo ?>" class="btn btn-warning btn-sm text-white">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <a onclick="return confirm('Hapus promo ini?')"
                                                        href="hapus.php?id=<?= $row->id_promo ?>"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile;
                                    else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                Tidak ada promo ditemukan.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>

                            </table>

                        </div>
                    </div>

                </div>
            </div>

            <?php include '../../partials/footer.php'; ?>
        </div>
    </div>
</div>

<?php include '../../partials/script.php'; ?>

<!-- DATATABLES JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $("#promoTable").DataTable({
            responsive: true,
            pageLength: 5,
            lengthChange: false,
            autoWidth: false,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
            },
            columnDefs: [{
                    responsivePriority: 1,
                    targets: -1
                }, // Aksi selalu muncul
                {
                    responsivePriority: 2,
                    targets: 2
                }, // Judul prioritas kedua
            ]
        });
    });
</script>