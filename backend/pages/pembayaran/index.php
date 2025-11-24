<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';

// Query data pembayaran
$query = "
    SELECT
        pb.id_pembayaran,
        pb.tanggal_pembayaran,
        pb.bulan_bayar,
        pb.biaya_admin,
        pb.total_bayar,
        p.nama_pelanggan,
        p.nomor_kwh,
        t.bulan,
        t.tahun,
        a.nama_admin
    FROM pembayaran pb
    LEFT JOIN pelanggan p ON pb.id_pelanggan = p.id_pelanggan
    LEFT JOIN tagihan t ON pb.id_tagihan = t.id_tagihan
    LEFT JOIN admin a ON pb.id_admin = a.id_admin
    ORDER BY pb.tanggal_pembayaran DESC
";

$result = mysqli_query($connect, $query) or die(mysqli_error($connect));
?>

<div class="layout-page">
    <?php include '../../partials/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h3 class="fw-bold mb-3"><i class="bx bx-credit-card text-primary me-2"></i> Data Pembayaran Listrik</h3>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white fw-semibold">
                    <i class="bx bx-table me-2"></i> Daftar Pembayaran
                </div>
                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table id="pembayaranTable" class="table table-striped table-hover align-middle">
                            <thead class="bg-light text-primary text-center">
                                <tr>
                                    <th>No</th>
                                    <th>ID Pembayaran</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Nomor KWH</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Status</th>
                                    <th>Bukti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td class="text-center"><?= htmlspecialchars($row['id_pembayaran']); ?></td>
                                            <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-'); ?></td>
                                            <td class="text-center"><?= htmlspecialchars($row['nomor_kwh'] ?? '-'); ?></td>
                                            <td class="text-end fw-semibold text-success">Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                            <td class="text-center"><?= !empty($row['tanggal_pembayaran']) ? date('d M Y', strtotime($row['tanggal_pembayaran'])) : '-'; ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-success">Lunas</span>
                                            </td>
                                            <td class="text-center">
                                                <?php if (!empty($row['bukti'])): ?>
                                                    <a href="../../../uploads/<?= $row['bukti'] ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                                        <i class="bx bx-image"></i> Lihat
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted small">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="detail.php?id=<?= $row['id_pembayaran']; ?>" class="btn btn-sm btn-outline-info"><i class="bx bx-show"></i></a>
                                                    <a href="edit.php?id=<?= $row['id_pembayaran']; ?>" class="btn btn-sm btn-outline-primary"><i class="bx bx-edit-alt"></i></a>
                                                    <a href="../../action/hapus.php?table=pembayaran&id=<?= $row['id_pembayaran']; ?>"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Yakin ingin menghapus pembayaran ini?');">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile;
                                else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">Belum ada data pembayaran.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include '../../partials/footer.php';
    include '../../partials/script.php';
    ?>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#pembayaranTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                pageLength: 10,
                lengthChange: false,
                ordering: true,
                autoWidth: false,
                responsive: true
            });
        });
    </script>