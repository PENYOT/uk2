<?php
// laporan_penggunaan.php - printable
include '../../../config/connection.php';

// ambil semua penggunaan (atau bisa tambah filter via GET)
$q = "
    SELECT u.*, p.nama_pelanggan
    FROM penggunaan u
    LEFT JOIN pelanggan p ON u.id_pelanggan = p.id_pelanggan
    ORDER BY u.tahun DESC, u.bulan DESC
";
$res = mysqli_query($connect, $q);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Penggunaan</title>
  <link rel="stylesheet" href="../../../sneat-1.0.0/assets/vendor/css/core.css">
  <style>
    body { font-family: Arial, Helvetica, sans-serif; padding: 20px; color:#333;}
    table { width:100%; border-collapse: collapse; margin-top:20px; }
    th, td { border:1px solid #ddd; padding:8px; text-align:left; }
    th { background:#f5f5f5; }
    .center { text-align:center; }
    @media print {
      .no-print { display:none; }
    }
  </style>
</head>
<body>
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <h3> Laporan Penggunaan Listrik</h3>
    <div class="no-print">
      <button onclick="window.print()">Print</button>
      <a href="index.php">Kembali</a>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Pelanggan</th>
        <th>Bulan</th>
        <th>Tahun</th>
        <th>Meter Awal</th>
        <th>Meter Akhir</th>
        <th>Total (KWh)</th>
      </tr>
    </thead>
    <tbody>
      <?php $no=1; while ($r = mysqli_fetch_assoc($res)): ?>
        <tr>
          <td class="center"><?= $no++ ?></td>
          <td><?= htmlspecialchars($r['nama_pelanggan']) ?></td>
          <td><?= htmlspecialchars($r['bulan']) ?></td>
          <td class="center"><?= $r['tahun'] ?></td>
          <td class="text-end"><?= number_format($r['meter_awal']) ?></td>
          <td class="text-end"><?= number_format($r['meter_ahir']) ?></td>
          <td class="text-end"><?= number_format($r['meter_ahir'] - $r['meter_awal']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
