<?php
include '../../partials/header.php';

// Ambil daftar pelanggan
$pelangganQuery = "SELECT id_pelanggan, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan ASC";
$pelangganResult = mysqli_query($connect, $pelangganQuery);

// Ambil filter pelanggan
$id_pelanggan = isset($_GET['id_pelanggan']) ? $_GET['id_pelanggan'] : '';

// Query data penggunaan
$query = "
    SELECT 
        g.id_penggunaan,
        g.bulan,
        g.tahun,
        g.meter_awal,
        g.meter_ahir,
        (g.meter_ahir - g.meter_awal) AS total_meter
    FROM penggunaan g
";

if ($id_pelanggan) {
    $query .= " WHERE g.id_pelanggan = '$id_pelanggan'";
}

$query .= " ORDER BY g.tahun DESC, g.bulan DESC";
$result = mysqli_query($connect, $query) or die(mysqli_error($connect));
?>


<?php include '../../partials/sidebar.php'; ?>

<div class="layout-page mt-5">

    <?php include '../../partials/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-warning">
                        <i class="bx bx-bolt-circle me-2"></i> Data Penggunaan Listrik
                    </h4>
                    <small class="text-muted">Pantau penggunaan listrik berdasarkan pelanggan.</small>
                </div>

                <a href="tambah.php" class="btn btn-warning btn-sm">
                    <i class="bx bx-plus"></i> Tambah Penggunaan
                </a>
            </div>
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php
                    if ($_GET['success'] == 'added') echo "Data penggunaan berhasil ditambahkan.";
                    if ($_GET['success'] == 'updated') echo "Data penggunaan berhasil diupdate.";
                    if ($_GET['success'] == 'deleted') echo "Data penggunaan berhasil dihapus.";
                    ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
            <!-- FILTER -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pilih Pelanggan</label>
                            <select name="id_pelanggan" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Tampilkan Semua --</option>

                                <?php while ($p = mysqli_fetch_assoc($pelangganResult)) : ?>
                                    <option value="<?= $p['id_pelanggan'] ?>"
                                        <?= ($id_pelanggan == $p['id_pelanggan']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nama_pelanggan']) ?>
                                    </option>
                                <?php endwhile; ?>

                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABLE -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-warning fw-semibold text-dark">
                    <i class="bx bx-table me-2"></i> Daftar Penggunaan
                </div>

                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table id="penggunaanTable" class="table table-hover align-middle">
                            <thead class="table-warning text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    <th>Meter Awal</th>
                                    <th>Meter Akhir</th>
                                    <th>Total Pemakaian (KWh)</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $chartData = [];

                                if (mysqli_num_rows($result) > 0):
                                    $no = 1;
                                    while ($row = mysqli_fetch_object($result)):

                                        $chartData[] = [
                                            'bulan' => $row->bulan . ' ' . $row->tahun,
                                            'total' => $row->total_meter
                                        ];
                                ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td class="text-center"><?= htmlspecialchars($row->bulan); ?></td>
                                            <td class="text-center"><?= htmlspecialchars($row->tahun); ?></td>
                                            <td class="text-end"><?= number_format($row->meter_awal); ?></td>
                                            <td class="text-end"><?= number_format($row->meter_ahir); ?></td>
                                            <td class="text-end fw-bold text-success"><?= number_format($row->total_meter); ?></td>
                                        </tr>

                                    <?php endwhile;
                                else: ?>

                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">
                                            Belum ada data penggunaan.
                                        </td>
                                    </tr>

                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CHART -->
            <?php if (!empty($chartData)) : ?>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-info text-white fw-semibold">
                        <i class="bx bx-line-chart me-2"></i> Grafik Pemakaian per Bulan
                    </div>
                    <div class="card-body">
                        <div id="chartContainer" style="height: 320px;"></div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php include '../../partials/footer.php'; ?>

</div>
</div>
</div>

<?php include '../../partials/script.php'; ?>

<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- APEXCHART -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    $(document).ready(function() {
        // INIT DATATABLE HANYA SEKALI
        $('#penggunaanTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
            },
            pageLength: 10,
            lengthChange: false,
            responsive: true
        });

        <?php if (!empty($chartData)) : ?>
            const chartData = <?= json_encode($chartData) ?>;

            new ApexCharts(document.querySelector("#chartContainer"), {
                chart: {
                    type: 'line',
                    height: 320
                },
                series: [{
                    name: "KWh",
                    data: chartData.map(c => c.total)
                }],
                xaxis: {
                    categories: chartData.map(c => c.bulan)
                },
                colors: ['#ffc107']
            }).render();
        <?php endif; ?>
    });
</script>