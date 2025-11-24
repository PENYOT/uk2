<div class="container-fluid py-5 bg-light">
    <div class="container py-5">

        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="text-primary text-uppercase">Promo Listrik</h6>
            <h1 class="mb-5">Promo Menarik Untuk Pelanggan</h1>
        </div>

        <div class="row g-4">

            <?php
            // PASTIKAN $connect SUDAH ADA SEBELUM INI
            $qPromo = "SELECT * FROM promo WHERE status='Aktif' ORDER BY id_promo DESC";
            $resPromo = mysqli_query($connect, $qPromo);
            while ($p = mysqli_fetch_assoc($resPromo)):
            ?>

                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">

                    <div class="promo-box bg-white shadow-sm rounded-4 overflow-hidden position-relative">

                        <?php if ($p['diskon'] > 0): ?>
                            <span class="badge bg-danger position-absolute top-0 start-0 m-3 px-3 py-2 fs-6 shadow">
                                -<?= $p['diskon'] ?>%
                            </span>
                        <?php endif; ?>

                        <div class="promo-img-wrapper">
                            <img src="../../storages/promo/<?= $p['gambar'] ?>"
                                class="promo-img"
                                alt="<?= $p['judul'] ?>">
                        </div>

                        <div class="p-4 d-flex flex-column flex-grow-1">
                            <h5 class="fw-bold text-dark mb-2"><?= $p['judul'] ?></h5>

                            <div class="mb-2 text-muted small">
                                <i class="fa fa-clock text-primary me-1"></i>
                                Berlaku sampai:
                                <span class="text-danger fw-bold">
                                    <?= date("d M Y", strtotime($p['tanggal_selesai'])) ?>
                                </span>
                            </div>

                            <p class="text-secondary small promo-description mb-auto">
                                <?= substr(strip_tags($p['deskripsi']), 0, 85) ?>...
                            </p>

                            <div class="d-grid mt-3">
                                <button class="btn btn-primary rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#promoModal<?= $p['id_promo'] ?>">
                                    Detail Promo
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal fade" id="promoModal<?= $p['id_promo'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content rounded-4 overflow-hidden">

                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title"><?= $p['judul'] ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="row">
                                    <div class="col-md-5 text-center mb-3 mb-md-0">
                                        <img src="../../storages/promo/<?= $p['gambar'] ?>"
                                            class="img-fluid rounded-3"
                                            alt="<?= $p['judul'] ?>">
                                    </div>

                                    <div class="col-md-7">
                                        <h4 class="fw-bold mb-3 text-primary"><?= $p['judul'] ?></h4>

                                        <div class="alert alert-info py-2 small">
                                            <i class="fa fa-calendar-alt text-primary me-2"></i>
                                            <strong>Periode:</strong>
                                            <?= date("d M Y", strtotime($p['tanggal_mulai'])) ?> –
                                            <?= date("d M Y", strtotime($p['tanggal_selesai'])) ?>
                                        </div>

                                        <h6 class="fw-bold mt-3">Deskripsi Promo:</h6>
                                        <p style="white-space: pre-wrap;"><?= htmlspecialchars($p['deskripsi']) ?></p>
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <?php $isLogin = isset($_SESSION['id_pelanggan']); // Asumsi cek login menggunakan id_pelanggan 
                                ?>
                                <?php if ($isLogin): ?>
                                    <a href="promo/claim_promo.php?id=<?= $p['id_promo'] ?>" class="btn btn-success rounded-pill px-4">
                                        Claim Promo
                                    </a>
                                <?php else: ?>
                                    <a href="../../backend/pages/auth/login.php?msg=Login untuk claim promo" class="btn btn-warning rounded-pill px-4">
                                        Login untuk Claim
                                    </a>
                                <?php endif; ?>

                                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
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
<style>
    /* PENGATURAN TINGGI GAMBAR */
    .promo-img-wrapper {
        width: 100%;
        height: 250px;
        /* Tinggi tetap */
        background: #f3f3f3;
        border-bottom: 3px solid #eee;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    /* Gambar tidak terpotong */
    .promo-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain !important;
        transition: .4s ease-in-out;
    }

    /* Hover zoom LEMBUT, tidak memotong */
    .promo-box:hover .promo-img {
        transform: scale(1.03);
    }

    /* Hover card naik */
    .promo-box {
        display: flex;
        flex-direction: column;
        height: 100%;
        /* MEMASTIKAN TINGGI CARD SAMA */
        transition: .3s ease;
        border-radius: 15px;
    }

    .promo-box>.p-4 {
        flex-grow: 1;
        /* Konten di tengah tumbuh mengisi ruang */
        display: flex;
        flex-direction: column;
    }

    .promo-box:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
    }

    /* PENGATURAN TINGGI DESKRIPSI */
    .promo-description {
        min-height: 70px;
        /* Tinggi minimum yang konsisten */
        flex-shrink: 0;
        overflow: hidden;
    }

    /* 💡 Tambahkan mb-auto untuk mendorong tombol ke bawah */
    .promo-description.mb-auto {
        margin-bottom: auto !important;
    }

    /* Modal gambar */
    .modal-body img {
        max-width: 100%;
        max-height: 320px;
        object-fit: contain !important;
        background: #f3f3f3;
        border-radius: 15px;
        padding: 8px;
    }
</style>