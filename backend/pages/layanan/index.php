<?php
// backend/pages/pelayanan/index.php
include '../../partials/header.php';
include '../../../config/connection.php';

// Query pelayanan + relasi admin
$query = "
    SELECT 
        p.*,
        a.username AS admin_pembuat,
        b.username AS admin_update
    FROM pelayanan p
    LEFT JOIN admin a ON p.dibuat_oleh = a.id_admin
    LEFT JOIN admin b ON p.diupdate_oleh = b.id_admin
    ORDER BY p.id_pelayanan DESC
";
$result = mysqli_query($connect, $query);
?>

<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">
    <?php include '../../partials/sidebar.php'; ?>
    <div class="layout-page">
      <?php include '../../partials/navbar.php'; ?>

      <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h4 class="fw-bold text-primary"><i class="bx bx-support me-2"></i> Manajemen Pelayanan</h4>
              <small class="text-muted">Kelola pelayanan yang diberikan kepada pelanggan.</small>
            </div>
            <a href="tambah.php" class="btn btn-primary btn-sm"><i class="bx bx-plus"></i> Tambah Pelayanan</a>
          </div>

          <div class="card mb-4">
            <div class="card-body">
              <input id="searchBox" class="form-control" placeholder="Cari pelayanan... (judul / deskripsi / status)">
            </div>
          </div>

          <div class="card">
            <div class="card-header bg-primary text-white">Daftar Pelayanan</div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="pelayananTable" class="table table-hover align-middle">
                  <thead class="table-primary text-center">
                    <tr>
                      <th>No</th>
                      <th>Gambar</th>
                      <th>Judul</th>
                      <th>Status</th>
                      <th>Pembuat</th>
                      <th>Update</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="tableContent">
                    <?php if (mysqli_num_rows($result) > 0):
                      $no = 1;
                      while ($row = mysqli_fetch_object($result)): ?>
                        <tr>
                          <td class="text-center"><?= $no++ ?></td>
                          <td class="text-center">
                            <img src="../../../storages/layanan/<?= htmlspecialchars($row->gambar ?: 'default.jpg') ?>"
                                 style="width:60px;height:60px;object-fit:cover" class="rounded shadow-sm" alt="img">
                          </td>
                          <td class="fw-semibold"><?= htmlspecialchars($row->judul) ?></td>
                          <td class="text-center">
                            <span class="badge bg-<?= $row->status === 'Aktif' ? 'success' : 'secondary' ?>">
                              <?= htmlspecialchars($row->status) ?>
                            </span>
                          </td>
                          <td><?= htmlspecialchars($row->admin_pembuat ?? '-') ?></td>
                          <td><?= htmlspecialchars($row->admin_update ?? '-') ?></td>
                          <td class="text-center">
                            <a href="detail.php?id=<?= $row->id_pelayanan ?>" class="btn btn-info btn-sm"><i class="bx bx-show"></i></a>
                            <a href="edit.php?id=<?= $row->id_pelayanan ?>" class="btn btn-warning btn-sm text-white"><i class="bx bx-edit"></i></a>
                            <a href="hapus.php?id=<?= $row->id_pelayanan ?>" onclick="return confirm('Hapus pelayanan ini?')" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></a>
                          </td>
                        </tr>
                      <?php endwhile;
                    else: ?>
                      <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data pelayanan.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
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

<!-- DataTables & AJAX search -->
<link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function(){
  let table = $('#pelayananTable').DataTable({
    language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json" },
    pageLength: 6,
    lengthChange: false,
    responsive: true
  });

  $('#searchBox').on('keyup', function(){
    const keyword = $(this).val();
    $.get('search.php', { keyword }, function(html){
      table.destroy();
      $('#tableContent').html(html);
      table = $('#pelayananTable').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json" },
        pageLength: 6,
        lengthChange: false,
        responsive: true
      });
    });
  });
});
</script>
