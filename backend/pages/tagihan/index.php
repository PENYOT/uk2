<?php
include '../../partials/header.php';
?>

<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">

    <?php include '../../partials/sidebar.php'; ?>

    <div class="layout-page">
      <?php include '../../partials/navbar.php'; ?>

      <div class="content-wrapper mt-5">
        <div class="container-xxl flex-grow-1 container-p-y">

          <!-- Header -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h4 class="fw-bold text-primary mb-1">
                <i class="bx bx-file me-2"></i> Data Tagihan Listrik
              </h4>
              <small class="text-muted">Kelola dan pantau seluruh data tagihan pelanggan di sistem pembayaran listrik.</small>
            </div>

            <!-- Tombol Generate Tagihan Bulanan -->
            <div>
              <a href="generate_otomatis.php"
                class="btn btn-warning btn-sm shadow-sm me-2"
                onclick="return confirm('Generate tagihan untuk semua pelanggan bulan ini?');">
                <i class="bx bx-refresh"></i> Generate Tagihan Bulanan
              </a>

              <a href="tambah.php" class="btn btn-primary btn-sm me-2 shadow-sm">
                <i class="bx bx-plus"></i> Tambah Tagihan
              </a>
              <button class="btn btn-outline-primary btn-sm shadow-sm" onclick="window.open('laporan_tagihan.php', '_blank')">
                <i class="bx bx-printer"></i> Cetak Laporan
              </button>
            </div>
          </div>

          <!-- Query Data Tagihan -->
          <?php
          $query = "
              SELECT 
                  t.id_tagihan,
                  t.bulan,
                  t.tahun,
                  t.jumlah_meter,
                  t.status,
                  p.nama_pelanggan,
                  p.nomor_kwh,
                  p.phone,
                  p.alamat
              FROM tagihan t
              LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
              ORDER BY t.id_tagihan DESC
          ";
          $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
          ?>

          <!-- Card Tabel Tagihan -->
          <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white fw-semibold">
              <i class="bx bx-table me-2"></i> Daftar Tagihan Listrik
            </div>

            <div class="card-body bg-white">
              <div class="table-responsive">
                <table id="tagihanTable" class="table table-striped align-middle" style="width:100%">
                  <thead class="bg-light text-primary text-center">
                    <tr>
                      <th>No</th>
                      <th>ID Tagihan</th>
                      <th>Nama Pelanggan</th>
                      <th>Nomor KWH</th>
                      <th>Bulan</th>
                      <th>Tahun</th>
                      <th>Jumlah Meter</th>
                      <th>Status</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                      <?php $no = 1;
                      while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                          <td class="text-center"><?= $no++; ?></td>
                          <td class="text-center fw-semibold"><?= $row['id_tagihan']; ?></td>
                          <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                          <td class="text-center"><?= $row['nomor_kwh']; ?></td>
                          <td class="text-center"><?= $row['bulan']; ?></td>
                          <td class="text-center"><?= $row['tahun']; ?></td>
                          <td class="text-end"><?= number_format($row['jumlah_meter']); ?> kWh</td>

                          <td class="text-center">
                            <?php if ($row['status'] == 'Lunas'): ?>
                              <span class="badge bg-success px-3 py-2"><i class="bx bx-check-circle me-1"></i>Lunas</span>
                            <?php else: ?>
                              <span class="badge bg-warning text-dark px-3 py-2"><i class="bx bx-time me-1"></i>Belum Bayar</span>
                            <?php endif; ?>
                          </td>

                          <td class="text-center">
                            <div class="btn-group">

                              <!-- Hapus -->
                              <a href="../../action/hapus.php?table=tagihan&id=<?= $row['id_tagihan']; ?>"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Yakin ingin menghapus tagihan ini?');">
                                <i class="bx bx-trash"></i>
                              </a>
                            </div>
                          </td>
                        </tr>
                      <?php endwhile; ?>
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

<!-- DataTables Script -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
  $(document).ready(function() {
    $('#tagihanTable').DataTable({
      dom: 'Bfrtip',
      buttons: [{
          extend: 'excel',
          className: 'btn btn-success btn-sm shadow-sm',
          text: '<i class="bx bx-file"></i> Excel'
        },
        {
          extend: 'pdf',
          className: 'btn btn-danger btn-sm shadow-sm',
          text: '<i class="bx bxs-file-pdf"></i> PDF'
        },
        {
          extend: 'print',
          className: 'btn btn-secondary btn-sm shadow-sm',
          text: '<i class="bx bx-printer"></i> Print'
        }
      ],
      language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data per halaman",
        zeroRecords: "Tidak ditemukan data tagihan",
        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
        infoEmpty: "Tidak ada data yang tersedia",
        infoFiltered: "(disaring dari _MAX_ total data)"
      },
      pageLength: 10,
      order: [
        [5, 'desc']
      ]
    });
  });
</script>