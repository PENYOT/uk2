<?php
include '../../../config/connection.php';
header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Pembayaran Listrik</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body { padding: 20px; }
    h4 { text-align: center; margin-bottom: 20px; }
    table th, table td { font-size: 14px; }
    @media print {
      .no-print { display: none; }
    }
  </style>
</head>
<body>
  <h4 class="fw-bold text-center text-primary">Laporan Pembayaran Listrik</h4>
  <table class="table table-bordered table-striped">
    <thead class="table-primary">
      <tr>
        <th>No</th>
        <th>ID Pembayaran</th>
        <th>Jumlah Bayar</th>
        <th>Tanggal</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $q = mysqli_query($connect, "SELECT * FROM pembayaran ORDER BY id_pembayaran DESC");
      $total = 0;
      while ($d = mysqli_fetch_assoc($q)) {
        echo "<tr>
          <td>{$no}</td>
          <td>{$d['id_pembayaran']}</td>
          <td>Rp " . number_format($d['jumlah_bayar'], 0, ',', '.') . "</td>
          <td>{$d['tanggal_bayar']}</td>
          <td>{$d['status']}</td>
        </tr>";
        if ($d['status'] === 'LUNAS') $total += $d['jumlah_bayar'];
        $no++;
      }
      ?>
      <tr class="fw-bold table-success">
        <td colspan="2">Total Uang Masuk</td>
        <td colspan="3">Rp <?= number_format($total, 0, ',', '.') ?></td>
      </tr>
    </tbody>
  </table>

  <div class="text-center mt-3 no-print">
    <button class="btn btn-primary" onclick="window.print()"><i class="bx bx-printer"></i> Cetak PDF</button>
  </div>
</body>
</html>
