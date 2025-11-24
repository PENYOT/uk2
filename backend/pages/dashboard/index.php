          <?php
          include '../../partials/header.php';
          // Query data pembayaran
          $qBayar = "SELECT * FROM pembayaran";
          $resBayar = mysqli_query($connect, $qBayar);
          $totalMasuk = 0;
          $totalBelum = 0;

          while ($b = mysqli_fetch_assoc($resBayar)) {
            if (strtoupper($b['status']) === 'LUNAS') {
              $totalMasuk += (int)$b['total_bayar'];
            } else {
              $totalBelum += (int)$b['total_bayar'];
            }
          }

          // ✅ Tambahkan inisialisasi variabel statistik di sini
          $totalTransaksi = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM pembayaran"));
          $totalPelanggan = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM pelanggan"));
          ?>


          <?php include '../../partials/sidebar.php'; ?>
          <div class="layout-page">
            <?php include '../../partials/navbar.php'; ?>

            <div class="content-wrapper">
              <div class="container-xxl flex-grow-1 container-p-y">

                <!-- WELCOME CARD -->
                <div class="card bg-gradient-primary text-white mb-4 shadow-lg border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                  <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                      <h4 class="fw-bold mb-1">Selamat Datang di Dashboard Pembayaran ⚡hallo gaesss</h4>
                      <p class="mb-0">Kelola pelanggan, tagihan, dan verifikasi pembayaran dengan mudah.</p>
                      <div class="mt-3">
                        <span class="badge bg-white text-primary me-2">Admin Panel</span>
                        <span class="badge bg-white text-primary">v2.0</span>
                      </div>
                    </div>
                    <img src="../../sneat-1.0.0/assets/img/illustrations/man-with-laptop-light.png" height="120" alt="Dashboard" />
                  </div>
                </div>

                <!-- STATISTICS SECTION -->
                <div class="row mb-4">
                  <!-- Total Uang Masuk -->
                  <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card shadow-lg border-0 text-white h-100"
                      style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 15px;">
                      <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="text-uppercase fw-bold mb-2">Total Uang Masuk</h6>
                          <h3 class="fw-bold mb-0">Rp <?= number_format($totalMasuk, 0, ',', '.') ?></h3>
                          <small>Dana yang sudah diterima</small>
                        </div>
                        <i class="bx bx-wallet bx-lg text-white"></i>
                      </div>
                    </div>
                  </div>

                  <!-- Total Belum Masuk -->
                  <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card shadow-lg border-0 text-white h-100"
                      style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); border-radius: 15px;">
                      <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="text-uppercase fw-bold mb-2">Total Belum Masuk</h6>
                          <h3 class="fw-bold mb-0">Rp <?= number_format($totalBelum, 0, ',', '.') ?></h3>
                          <small>Dana yang belum diterima</small>
                        </div>
                        <i class="bx bx-time bx-lg text-white"></i>
                      </div>
                    </div>
                  </div>

                  <!-- Total Transaksi -->
                  <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card shadow-lg border-0 text-white h-100"
                      style="background: linear-gradient(135deg, #007bff 0%, #6610f2 100%); border-radius: 15px;">
                      <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="text-uppercase fw-bold mb-2">Total Transaksi</h6>
                          <h3 class="fw-bold mb-0"><?= $totalTransaksi ?></h3>
                          <small>Jumlah pembayaran</small>
                        </div>
                        <i class="bx bx-receipt bx-lg text-white"></i>
                      </div>
                    </div>
                  </div>

                  <!-- Pelanggan Aktif -->
                  <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card shadow-lg border-0 text-white h-100"
                      style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); border-radius: 15px;">
                      <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="text-uppercase fw-bold mb-2">Pelanggan Aktif</h6>
                          <h3 class="fw-bold mb-0"><?= $totalPelanggan ?></h3>
                          <small>Total pengguna terdaftar</small>
                        </div>
                        <i class="bx bx-user bx-lg text-white"></i>
                      </div>
                    </div>
                  </div>
                </div>


                <!-- TABLE VERIFIKASI PEMBAYARAN -->
                <div class="card shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
                  <div class="card-header bg-gradient-primary text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="mb-0 fw-bold">
                        <i class="bx bx-check-circle me-2"></i>Verifikasi Pembayaran
                      </h5>
                      <div>
                        <button class="btn btn-light btn-sm me-2 shadow-sm" onclick="window.open('laporan_pembayaran.php', '_blank')">
                          <i class="bx bx-printer"></i> Cetak Laporan
                        </button>
                        <button class="btn btn-outline-light btn-sm shadow-sm" id="refreshData">
                          <i class="bx bx-refresh"></i> Refresh
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-hover mb-0">
                        <thead class="bg-light">
                          <tr>
                            <th class="border-0 fw-bold text-primary">#</th>
                            <th class="border-0 fw-bold text-primary">ID Pembayaran</th>
                            <th class="border-0 fw-bold text-primary">Jumlah Bayar</th>
                            <th class="border-0 fw-bold text-primary">Tanggal</th>
                            <th class="border-0 fw-bold text-primary">Status</th>
                            <th class="border-0 fw-bold text-primary">Bukti</th>
                            <th class="border-0 fw-bold text-primary">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $no = 1;
                          // Pastikan nama kolom sesuai di database kamu (ubah jika perlu)
                        $resBayar = mysqli_query($connect, "SELECT * FROM pembayaran WHERE status != 'LUNAS' ORDER BY id_pembayaran DESC LIMIT 10");
                          if (mysqli_num_rows($resBayar) > 0) {
                            while ($row = mysqli_fetch_assoc($resBayar)) {
                          ?>
                              <tr class="border-bottom border-light">
                                <td class="fw-semibold text-muted"><?= $no++ ?></td>
                                <td>
                                  <span class="badge bg-light text-dark fw-semibold px-3 py-2">
                                    <?= htmlspecialchars($row['id_pembayaran']) ?>
                                  </span>
                                </td>
                                <td class="fw-bold text-success">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                                <td class="text-muted">
                                  <i class="bx bx-calendar me-1"></i>
                                  <?= htmlspecialchars($row['tanggal_pembayaran'] ?? '-') ?>
                                </td>
                                <td>
                                  <span class="badge px-3 py-2 fw-semibold <?= strtoupper($row['status']) == 'LUNAS' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                    <i class="bx bx-<?= strtoupper($row['status']) == 'LUNAS' ? 'check' : 'time' ?> me-1"></i>
                                    <?= htmlspecialchars($row['status']) ?>
                                  </span>
                                </td>
                                <td>
                                  <?php if (!empty($row['bukti'])): ?>
                                    <a href="../../../assets/bukti/<?= $row['bukti'] ?>" target="_blank" class="btn btn-outline-info btn-sm rounded-pill px-3">
                                      <i class="bx bx-image"></i> Lihat
                                    </a>
                                  <?php else: ?>
                                    <span class="text-muted small">
                                      <i class="bx bx-x-circle"></i> Belum ada
                                    </span>
                                  <?php endif; ?>
                                </td>
                                <td>
                                  <?php if (strtoupper($row['status']) != 'LUNAS'): ?>
                                    <button class="btn btn-success btn-sm rounded-pill px-3 verifikasiBtn" data-id="<?= $row['id_pembayaran'] ?>">
                                      <i class="bx bx-check"></i> Verifikasi
                                    </button>
                                  <?php else: ?>
                                    <span class="text-success small fw-semibold">
                                      <i class="bx bx-check-circle"></i> Terverifikasi
                                    </span>
                                  <?php endif; ?>
                                </td>
                              </tr>
                          <?php
                            }
                          } else {
                            echo "<tr><td colspan='7' class='text-center text-muted py-5'><div class='py-4'><i class='bx bx-inbox bx-lg mb-3 d-block text-muted'></i><h6 class='text-muted'>Belum ada data pembayaran</h6><p class='text-muted small mb-0'>Data pembayaran akan muncul di sini setelah ada transaksi</p></div></td></tr>";
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- SCRIPT UNTUK VERIFIKASI & REFRESH -->
            <script>
              document.addEventListener("DOMContentLoaded", function() {
                const verifikasiBtns = document.querySelectorAll(".verifikasiBtn");

                verifikasiBtns.forEach(btn => {
                  btn.addEventListener("click", function() {
                    const id = this.getAttribute("data-id");
                    if (confirm("Apakah Anda yakin ingin memverifikasi pembayaran ini?")) {
                      fetch(`verifikasi.php?id=${id}`)
                        .then(res => res.json())
                        .then(data => {
                          if (data.success) {
                            alert("Pembayaran berhasil diverifikasi!");
                            location.reload();
                          } else {
                            alert("Gagal memverifikasi: " + data.message);
                          }
                        });
                    }
                  });
                });

                document.getElementById("refreshData").addEventListener("click", () => location.reload());
              });
            </script>

            <?php include '../../partials/footer.php'; ?>
          </div>