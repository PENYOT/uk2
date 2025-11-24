<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';

// Query data admin
$query = "SELECT * FROM admin ORDER BY id_admin ASC";
$result = mysqli_query($connect, $query) or die(mysqli_error($connect));

// Statistik admin
$totalAdmin = mysqli_num_rows($result);
$totalPelanggan = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM pelanggan"));
$totalPembayaran = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM pembayaran"));
?>

<div class="layout-page">
  <?php include '../../partials/navbar.php'; ?>

  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <h3 class="fw-bold mb-3"><i class="bx bx-shield text-primary me-2"></i> Panel Admin</h3>

      <!-- Statistics Cards -->
      <div class="row mb-4">
        <div class="col-md-4 mb-3">
          <div class="card shadow-sm border-0 text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h6 class="text-uppercase fw-bold mb-2">Total Admin</h6>
                <h3 class="fw-bold mb-0"><?= $totalAdmin ?></h3>
                <small>Pengguna admin aktif</small>
              </div>
              <i class="bx bx-shield bx-lg text-white"></i>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="card shadow-sm border-0 text-white h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 15px;">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h6 class="text-uppercase fw-bold mb-2">Total Pelanggan</h6>
                <h3 class="fw-bold mb-0"><?= $totalPelanggan ?></h3>
                <small>Pengguna terdaftar</small>
              </div>
              <i class="bx bx-user bx-lg text-white"></i>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="card shadow-sm border-0 text-white h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 15px;">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h6 class="text-uppercase fw-bold mb-2">Total Pembayaran</h6>
                <h3 class="fw-bold mb-0"><?= $totalPembayaran ?></h3>
                <small>Transaksi pembayaran</small>
              </div>
              <i class="bx bx-credit-card bx-lg text-white"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Admin Management -->
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white fw-semibold d-flex justify-content-between align-items-center">
          <span><i class="bx bx-user-circle me-2"></i> Data Admin</span>
          <a href="../auth/register.php" class="btn btn-light btn-sm">
            <i class="bx bx-plus"></i> Tambah Admin
          </a>
        </div>
        <div class="card-body bg-white">
          <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-primary text-center">
                  <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1;
                  while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                      <td class="text-center"><?= $no++; ?></td>
                      <td class="text-center">
                        <span class="badge bg-light text-dark fw-semibold px-3 py-2">
                          <?= htmlspecialchars($row['username']); ?>
                        </span>
                      </td>
                      <td class="fw-semibold"><?= htmlspecialchars($row['nama_admin']); ?></td>
                      <td class="text-center">
                        <span class="badge bg-success px-3 py-2">
                          <i class="bx bx-check-circle me-1"></i>Aktif
                        </span>
                      </td>
                      <td class="text-center">
                        <div class="btn-group">
                          <a href="edit.php?id=<?= $row['id_admin']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-edit-alt"></i> Edit
                          </a>
                          <a href="hapus.php?id=<?= $row['id_admin']; ?>" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Yakin ingin menghapus admin ini?');">
                            <i class="bx bx-trash"></i> Hapus
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-center text-muted py-5">
              <i class="bx bx-user-x bx-lg mb-3 d-block text-muted"></i>
              <h6 class="text-muted">Belum ada data admin</h6>
              <p class="text-muted small mb-0">Tambah admin baru untuk mengelola sistem</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- System Info -->
      <div class="row mt-4">
        <div class="col-md-6">
          <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-info text-white fw-semibold">
              <i class="bx bx-info-circle me-2"></i> Informasi Sistem
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <div class="text-center">
                    <i class="bx bx-server bx-lg text-info mb-2"></i>
                    <h6 class="fw-bold">Database</h6>
                    <small class="text-muted">MySQL Active</small>
                  </div>
                </div>
                <div class="col-6">
                  <div class="text-center">
                    <i class="bx bx-shield-check bx-lg text-success mb-2"></i>
                    <h6 class="fw-bold">Security</h6>
                    <small class="text-muted">Protected</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-warning text-dark fw-semibold">
              <i class="bx bx-bell me-2"></i> Notifikasi Sistem
            </div>
            <div class="card-body">
              <div class="alert alert-light border-0 mb-0">
                <i class="bx bx-check-circle text-success me-2"></i>
                <small>Sistem berjalan dengan normal</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  include '../../partials/footer.php';
  include '../../partials/script.php';
  ?>
</div>