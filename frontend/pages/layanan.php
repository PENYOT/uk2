<?php
include '../../config/connection.php';
?>

<!-- Pelayanan Start -->
<div class="container-xxl py-5">
    <div class="container py-5">

        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="text-warning text-uppercase">Pelayanan Kami</h6>
            <h1 class="mb-5">Layanan Terbaik Untuk Anda</h1>
        </div>

        <div class="row g-4">

            <?php
            $qService = "SELECT * FROM pelayanan WHERE status='Aktif' ORDER BY id_pelayanan DESC";
            $resService = mysqli_query($connect, $qService);

            while ($srv = mysqli_fetch_assoc($resService)):
            ?>

                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">

                    <div class="service-card shadow-sm">

                        <!-- Gambar -->
                        <div class="service-image-wrapper">
                            <img src="../../storages/layanan/<?= $srv['gambar'] ?>"
                                class="service-image"
                                alt="<?= $srv['judul'] ?>">
                        </div>

                        <!-- Isi Card -->
                        <div class="service-content p-4">

                            <h4 class="fw-bold mb-3"><?= $srv['judul'] ?></h4>

                            <p class="service-text">
                                <?= substr($srv['deskripsi'], 0, 120) ?>...
                            </p>

                            <!-- Button Modal -->
                            <?php if ($isLogin): ?>
                                <a href="pelayanan_detail.php?id=<?= $srv['id_pelayanan'] ?>" class="btn-service">
                                    Selengkapnya <i class="fa fa-arrow-right ms-2"></i>
                                </a>
                            <?php else: ?>
                                <a href="../auth/login.php?msg=Silakan login untuk melihat detail pelayanan" class="btn-service text-danger">
                                    Login untuk melihat <i class="fa fa-lock ms-2"></i>
                                </a>
                            <?php endif; ?>

                        </div>

                    </div>

                </div>

                <!-- ========================================= -->
                <!-- ==========  MODAL DETAIL PELAYANAN  ====== -->
                <!-- ========================================= -->
                <div class="modal fade" id="modalPelayanan<?= $srv['id_pelayanan'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content rounded-4 overflow-hidden">

                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title fw-bold"><?= $srv['judul'] ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body p-4">

                                <div class="row g-4">

                                    <!-- Gambar -->
                                    <div class="col-md-5 text-center">
                                        <div class="detail-img-wrapper">
                                            <img src="../../storages/layanan/<?= $srv['gambar'] ?>"
                                                class="detail-img rounded">
                                        </div>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="col-md-7">
                                        <h3 class="fw-bold"><?= $srv['judul'] ?></h3>

                                        <p class="text-secondary mt-2" style="font-size: 17px;">
                                            <?= nl2br($srv['deskripsi']) ?>
                                        </p>

                                        <span class="badge bg-<?= $srv['status'] == 'Aktif' ? 'success' : 'secondary' ?> px-3 py-2 mt-3">
                                            Status: <?= $srv['status'] ?>
                                        </span>
                                    </div>

                                </div>

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                    Tutup
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endwhile; ?>

        </div>
    </div>
</div>
<!-- Pelayanan End -->


<style>
    /* CARD */
    .service-card {
        background: #ffffff;
        border-radius: 18px;
        transition: 0.3s ease;
        overflow: hidden;
    }

    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 35px rgba(0, 0, 0, 0.15);
    }

    /* GAMBAR CARD – TIDAK TERPOTONG */
    .service-image-wrapper {
        height: 230px;
        background: #f3f3f3;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .service-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* TEXT */
    .service-text {
        color: #555;
        min-height: 70px;
    }

    /* BUTTON */
    .btn-service {
        font-weight: 600;
        color: #ff8800;
        text-decoration: none;
        background: transparent;
        border: none;
        padding: 0;
        transition: 0.3s ease;
    }

    .btn-service:hover {
        color: #000;
        letter-spacing: 1px;
    }

    /* MODAL DETAIL */
    .detail-img-wrapper {
        width: 100%;
        height: 300px;
        background: #f7f7f7;
        border-radius: 18px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .detail-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>