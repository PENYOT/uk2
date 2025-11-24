<?php
// backend/pages/pelayanan/search.php
include '../../../config/connection.php';

$keyword = isset($_GET['keyword']) ? trim(mysqli_real_escape_string($connect, $_GET['keyword'])) : '';

$sql = "SELECT p.*, a.username AS admin_pembuat, b.username AS admin_update
        FROM pelayanan p
        LEFT JOIN admin a ON p.dibuat_oleh = a.id_admin
        LEFT JOIN admin b ON p.diupdate_oleh = b.id_admin";

if ($keyword !== '') {
    $sql .= " WHERE p.judul LIKE '%$keyword%' OR p.deskripsi LIKE '%$keyword%' OR p.status LIKE '%$keyword%'";
}
$sql .= " ORDER BY p.id_pelayanan DESC";

$res = mysqli_query($connect, $sql);

if ($res && mysqli_num_rows($res) > 0) {
    $no = 1;
    while ($row = mysqli_fetch_object($res)) {
        ?>
        <tr>
          <td class="text-center"><?= $no++ ?></td>
          <td class="text-center">
            <img src="../../../storages/layanan/<?= htmlspecialchars($row->gambar ?: 'default.jpg') ?>" style="width:60px;height:60px;object-fit:cover" class="rounded shadow-sm" alt="img">
          </td>
          <td class="fw-semibold"><?= htmlspecialchars($row->judul) ?></td>
          <td class="text-center"><span class="badge bg-<?= $row->status==='Aktif'?'success':'secondary' ?>"><?= $row->status ?></span></td>
          <td><?= htmlspecialchars($row->admin_pembuat ?? '-') ?></td>
          <td><?= htmlspecialchars($row->admin_update ?? '-') ?></td>
          <td class="text-center">
            <a href="detail.php?id=<?= $row->id_pelayanan ?>" class="btn btn-info btn-sm"><i class="bx bx-show"></i></a>
            <a href="edit.php?id=<?= $row->id_pelayanan ?>" class="btn btn-warning btn-sm text-white"><i class="bx bx-edit"></i></a>
            <a href="hapus.php?id=<?= $row->id_pelayanan ?>" onclick="return confirm('Hapus pelayanan ini?')" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></a>
          </td>
        </tr>
        <?php
    }
} else {
    echo '<tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data pelayanan.</td></tr>';
}
