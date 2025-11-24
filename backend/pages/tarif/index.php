<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';

// Ambil semua data tarif
$query = "SELECT * FROM tarif ORDER BY CAST(daya AS UNSIGNED) ASC";
$result = mysqli_query($connect, $query) or die(mysqli_error($connect));
?>

<div class="layout-page">
    <?php include '../../partials/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <h3 class="fw-bold mb-4">
                <i class="bx bx-bulb text-warning me-2"></i> Data Tarif & Kalkulator Listrik
            </h3>

            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahTarif">
                <i class="bx bx-plus"></i> Tambah Tarif
            </button>

            <!-- CARD: DAFTAR TARIF -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-warning text-dark fw-semibold">
                    <i class="bx bx-table me-2"></i> Daftar Tarif Listrik
                </div>
                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table id="tarifTable" class="table table-striped table-hover align-middle">
                            <thead class="bg-light text-primary text-center">
                                <tr>
                                    <th>No</th>
                                    <th>ID Tarif</th>
                                    <th>Daya (VA)</th>
                                    <th>Tarif per kWh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td class="text-center"><?= htmlspecialchars($row['id_tarif']); ?></td>
                                            <td class="text-center fw-semibold">
                                                <?= is_numeric($row['daya']) ? number_format($row['daya'], 0, ',', '.') . ' VA' : htmlspecialchars($row['daya']); ?>
                                            </td>
                                            <td class="text-end text-success fw-bold">
                                                Rp <?= is_numeric($row['tarifperkwh']) ? number_format($row['tarifperkwh'], 0, ',', '.') : htmlspecialchars($row['tarifperkwh']); ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="edit.php?id=<?= $row['id_tarif']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                                <a href="../../action/hapus.php?table=tarif&id=<?= $row['id_tarif']; ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Menghapus tarif akan menghapus pelanggan dan data terkait. Lanjutkan?');">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile;
                                else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada data tarif.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CARD: KALKULATOR LISTRIK -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-primary text-white fw-semibold">
                    <i class="bx bx-calculator me-2"></i> Kalkulator Tagihan Listrik
                </div>
                <div class="card-body bg-white">
                    <form id="kalkulatorForm" class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Daya (VA)</label>
                            <select id="daya" class="form-select" required>
                                <option value="" disabled selected>Pilih Daya</option>
                                <?php
                                $res2 = mysqli_query($connect, "SELECT * FROM tarif ORDER BY CAST(daya AS UNSIGNED) ASC");
                                while ($row = mysqli_fetch_assoc($res2)): ?>
                                    <option
                                        value="<?= (float)$row['tarifperkwh']; ?>"
                                        data-daya="<?= htmlspecialchars($row['daya']); ?>">
                                        <?= htmlspecialchars($row['daya']); ?> VA - Rp <?= number_format((float)$row['tarifperkwh'], 0, ',', '.'); ?>/kWh
                                    </option>
                                <?php endwhile; ?>
                            </select>

                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Pemakaian (kWh)</label>
                            <input type="number" id="pemakaian" class="form-control" placeholder="Masukkan jumlah kWh" required min="1">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Biaya Admin (Rp)</label>
                            <input type="number" id="biaya_admin" class="form-control" value="2500" required>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="hitungBtn" class="btn btn-success w-100">
                                <i class="bx bx-calculator me-1"></i> Hitung Tagihan
                            </button>
                        </div>
                    </form>

                    <!-- HASIL PERHITUNGAN -->
                    <div class="mt-4 text-center" id="hasilContainer" style="display:none;">
                        <div class="alert alert-success shadow-sm rounded-3 p-4">
                            <h5 class="fw-bold mb-2"><i class="bx bx-money me-2"></i> Estimasi Tagihan</h5>
                            <p class="mb-1">Daya: <span id="hasilDaya" class="fw-semibold text-primary"></span></p>
                            <p class="mb-1">Pemakaian: <span id="hasilPemakaian" class="fw-semibold text-primary"></span> kWh</p>
                            <p class="mb-0 fs-5 fw-bold">Total Bayar: <span id="hasilTotal" class="text-success"></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD: GRAFIK SIMULASI -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-info text-white fw-semibold">
                    <i class="bx bx-line-chart me-2"></i> Simulasi Estimasi Biaya per Bulan
                </div>
                <div class="card-body bg-white">
                    <div id="chartContainer" style="height: 320px;"></div>
                </div>
            </div>

            <!-- MODAL TAMBAH TARIF -->
            <div class="modal fade" id="modalTambahTarif" tabindex="-1" aria-labelledby="modalTambahTarifLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 shadow-sm">

                        <div class="modal-header bg-primary text-white rounded-top-4">
                            <h5 class="modal-title" id="modalTambahTarifLabel">
                                <i class="bx bx-plus-circle me-1"></i> Tambah Tarif Listrik
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="tambah_action.php" method="POST">
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Daya (VA)</label>
                                    <input type="text" class="form-control" name="daya" placeholder="Contoh: 450" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tarif per kWh (Rp)</label>
                                    <input type="number" class="form-control" name="tarifperkwh" step="0.01" placeholder="Contoh: 1352.00" required>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                    <i class="bx bx-x"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bx bx-save"></i> Simpan
                                </button>
                            </div>

                        </form>

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

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        $(document).ready(function() {
            // === DataTable ===
            $('#tarifTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                pageLength: 5,
                responsive: true,
                lengthChange: false,
                columnDefs: [{
                    targets: [0, 1, 2, 3, 4],
                    className: "align-middle"
                }]
            });

            $('#hitungBtn').on('click', function() {
                const tarif = parseFloat($('#daya').val());
                const daya = $('#daya option:selected').data('daya');
                const pemakaian = parseFloat($('#pemakaian').val());
                const admin = parseFloat($('#biaya_admin').val());

                if (isNaN(tarif) || isNaN(pemakaian) || isNaN(admin)) {
                    alert('Mohon isi semua data dengan benar!');
                    return;
                }

                const total = (tarif * pemakaian) + admin;

                // tampilkan hasil
                $('#hasilDaya').text(daya);
                $('#hasilPemakaian').text(pemakaian.toLocaleString('id-ID'));
                $('#hasilTotal').text('Rp ' + total.toLocaleString('id-ID'));
                $('#hasilContainer').fadeIn();

                // update grafik
                updateChart(pemakaian, tarif, admin);
            });

            // === Grafik Simulasi ===
            let chartOptions = {
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: {
                        show: false
                    },
                },
                colors: ['#007bff', '#ffc107', '#28a745'],
                series: [{
                    name: 'Estimasi Tagihan',
                    data: [0, 0, 0, 0, 0, 0]
                }],
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']
                },
                yaxis: {
                    labels: {
                        formatter: (val) => 'Rp ' + val.toLocaleString('id-ID')
                    }
                },
                title: {
                    text: 'Simulasi Biaya 6 Bulan ke Depan',
                    style: {
                        fontSize: '14px'
                    }
                },
                tooltip: {
                    y: {
                        formatter: (val) => 'Rp ' + val.toLocaleString('id-ID')
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#chartContainer"), chartOptions);
            chart.render();

            function updateChart(pemakaian, tarif, admin) {
                const estimasi = [];
                for (let i = 0; i < 6; i++) {
                    estimasi.push(((pemakaian + (i * 5)) * tarif) + admin);
                }
                chart.updateSeries([{
                    name: 'Estimasi Tagihan',
                    data: estimasi
                }]);
            }
        });
    </script>

    <style>
        .content-wrapper {
            margin-left: 250px;
            background-color: #f8f9fa;
            min-height: 100vh;
            padding-top: 90px;
        }

        .card:hover {
            transform: translateY(-3px);
            transition: 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 6px 10px;
        }
    </style>
</div>