<?php
// backend/pages/pelayanan/edit.php
include '../../partials/header.php';
include '../../../config/connection.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) { header("Location: index.php"); exit; }

$q = "SELECT * FROM pelayanan WHERE id_pelayanan = $id LIMIT 1";
$res = mysqli_query($connect, $q);
if (!mysqli_num_rows($res)) { header("Location: index.php"); exit; }
$row = mysqli_fetch_object($res);

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($connect, trim($_POST['judul'] ?? ''));
    $deskripsi = mysqli_real_escape_string($connect, trim($_POST['deskripsi'] ?? ''));
    $status = in_array($_POST['status'] ?? '', ['Aktif','Nonaktif']) ? $_POST['status'] : 'Aktif';
    $gambar = $row->gambar ?: 'default.jpg';

    // upload gambar baru bila ada
    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $allowed)) {
            $err = "Format gambar tidak didukung.";
        } else {
            $newName = 'pel_'.time().'_'.rand(1000,9999).'.'.$ext;
            $dest = '../../../storages/layanan/'.$newName;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                // hapus lama jika bukan default
                if ($gambar && $gambar !== 'default.jpg' && file_exists('../../../storages/layanan/'.$gambar)) {
                    @unlink('../../../storages/layanan/'.$gambar);
                }
                $gambar = $newName;
            } else $err = "Gagal upload gambar.";
        }
    }

    if (!$err) {
        $adminID = $_SESSION['id_admin'] ?? null;
        $update = "UPDATE pelayanan SET judul='$judul', deskripsi='$deskripsi', gambar='$gambar', status='$status', diupdate_oleh=".($adminID?intval($adminID):'NULL')." WHERE id_pelayanan = $id";
        if (mysqli_query($connect, $update)) {
            header("Location: index.php?success=updated");
            exit;
        } else $err = "Gagal update: ".mysqli_error($connect);
    }
}
?>

<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">
    <?php include '../../partials/sidebar.php'; ?>
    <div class="layout-page">
      <?php include '../../partials/navbar.php'; ?>

      <div class="content-wrapper">
        <div class="container-xxl p-4">
          <h4 class="mb-4"><i class="bx bx-edit me-2"></i> Edit Pelayanan</h4>

          <?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>

          <div class="card p-4">
            <form method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label class="form-label">Judul</label>
                <input name="judul" class="form-control" required value="<?= htmlspecialchars($row->judul) ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="6"><?= htmlspecialchars($row->deskripsi) ?></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Gambar Saat Ini</label><br>
                <img src="../../../storages/layanan/<?= htmlspecialchars($row->gambar ?: 'default.jpg') ?>" style="width:180px;height:120px;object-fit:cover" class="rounded mb-2">
                <input type="file" name="gambar" class="form-control" onchange="previewImg(event)">
                <img id="imgPreview" src="#" style="display:none;width:150px;height:100px;object-fit:cover;margin-top:10px;" class="rounded shadow-sm">
              </div>

              <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                  <option value="Aktif" <?= $row->status === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                  <option value="Nonaktif" <?= $row->status === 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
              </div>

              <button class="btn btn-primary"><i class="bx bx-save"></i> Update</button>
              <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
          </div>

        </div>
      </div>

      <?php include '../../partials/footer.php'; ?>
    </div>
  </div>
</div>

<?php include '../../partials/script.php'; ?>
<script>
function previewImg(e){
  const reader = new FileReader();
  reader.onload = function(){ 
    const img = document.getElementById('imgPreview');
    img.src = reader.result; img.style.display = 'block';
  };
  reader.readAsDataURL(e.target.files[0]);
}
</script>
