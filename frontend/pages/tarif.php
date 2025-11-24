<?php
include '../../config/connection.php';

// Ambil data tarif
$qTarif = mysqli_query($connect, "SELECT * FROM tarif ORDER BY daya ASC");
?>

<!-- Pricing Start -->
<div class="container-xxl py-5">
    <div class="container py-5">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="text-secondary text-uppercase">Tarif Listrik</h6>
            <h1 class="mb-5">Daftar Tarif Daya & Biaya per kWh</h1>
        </div>

        <div class="row g-4">
            <?php while ($t = mysqli_fetch_object($qTarif)): ?>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="price-item">

                        <!-- Header -->
                        <div class="border-bottom p-4 mb-4">
                            <h5 class="text-primary mb-1">Daya <?= htmlspecialchars($t->daya); ?> VA</h5>

                            <h1 class="display-5 mb-0">
                                <small class="align-top" style="font-size: 22px; line-height:45px;">Rp</small>
                                <?= number_format($t->tarifperkwh, 0, ',', '.'); ?>
                                <small class="align-bottom" style="font-size: 16px; line-height:40px;">/ kWh</small>
                            </h1>
                        </div>

                        <!-- Body -->
                        <div class="p-4 pt-0">
                            <p><i class="fa fa-check text-success me-3"></i>Daya Listrik: <?= htmlspecialchars($t->daya); ?> VA</p>
                            <p><i class="fa fa-check text-success me-3"></i>Tarif Dasar per kWh</p>
                            <p><i class="fa fa-check text-success me-3"></i>Berlaku Untuk Semua Pelanggan</p>

                            <button class="btn-detail mt-2" data-bs-toggle="modal" data-bs-target="#modalTarif<?= $t->id_tarif; ?>">
                                <i class="fa fa-search"></i> <span>Detail Tarif</span>
                            </button>
                        </div>

                    </div>
                </div>

                <!-- =======================
        MODAL DETAIL TARIF
        ======================== -->
                <div class="modal fade" id="modalTarif<?= $t->id_tarif; ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Detail Tarif <?= htmlspecialchars($t->daya); ?> VA</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p class="mb-3">
                                    Berikut adalah rincian lengkap tarif untuk daya <strong><?= $t->daya; ?> VA</strong>.
                                    Informasi ini dapat membantu Anda memprediksi penggunaan listrik secara lebih akurat dan efisien.
                                </p>

                                <table class="table table-bordered">
                                    <tr>
                                        <th>Daya Listrik</th>
                                        <td><?= $t->daya; ?> VA</td>
                                    </tr>
                                    <tr>
                                        <th>Tarif per kWh</th>
                                        <td>Rp <?= number_format($t->tarifperkwh, 0, ',', '.'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td>Tarif ini berlaku untuk seluruh pelanggan dengan kategori daya terkait dan mengikuti kebijakan tarif resmi yang berlaku.</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
        </div>
    </div>
</div>
<!-- Pricing End -->

<style>
    /* === Tombol Detail Modern === */
    .btn-detail {
        position: relative;
        display: inline-block;
        padding: 10px 22px;
        background: #0056d6;
        color: #fff;
        border-radius: 50px;
        overflow: hidden;
        font-weight: 500;
        transition: 0.4s ease;
        border: none;
    }

    .btn-detail i {
        margin-right: 6px;
    }

    /* Animasi hover */
    .btn-detail:hover {
        background: #003ea0;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 86, 214, 0.45);
    }

    .btn-detail span {
        position: relative;
        z-index: 2;
    }

    .btn-detail::before {
        content: "";
        position: absolute;
        width: 0%;
        height: 100%;
        background: rgba(255, 255, 255, 0.25);
        top: 0;
        left: 0;
        transition: 0.4s;
    }

    .btn-detail:hover::before {
        width: 100%;
    }

    /* === Modal Glassmorphism === */
    .modal-content {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(12px);
        border-radius: 18px !important;
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 25px 45px rgba(0, 0, 0, 0.15);
        animation: fadeUp 0.35s ease-out;
    }

    /* Header Modern */
    .modal-header {
        border-bottom: none;
        padding: 20px 25px;
        background: linear-gradient(135deg, #0062ff, #001a63);
        border-radius: 18px 18px 0 0 !important;
    }

    /* Footer */
    .modal-footer {
        border-top: none;
        padding: 15px 25px;
    }

    /* Table Styling */
    .modal-body table {
        border-radius: 10px;
        overflow: hidden;
    }

    .modal-body th {
        background: #e9f0ff;
        width: 40%;
    }

    .modal-body td {
        background: #f8faff;
    }

    /* Animasi muncul modal */
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>