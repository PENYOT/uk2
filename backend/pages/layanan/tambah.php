<?php
// backend/pages/pelayanan/tambah.php
include '../../partials/header.php';
include '../../../config/connection.php';

$err = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($connect, trim($_POST['judul'] ?? ''));
    $deskripsi = mysqli_real_escape_string($connect, trim($_POST['deskripsi'] ?? ''));
    $status = in_array($_POST['status'] ?? '', ['Aktif','Nonaktif']) ? $_POST['status'] : 'Aktif';
    $gambar = 'default.jpg';

    // upload gambar jika ada
    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $allowed)) {
            $err = "Format gambar tidak didukung (jpg,jpeg,png,webp).";
        } else {
            $newName = 'pel_'.time().'_'.rand(1000,9999).'.'.$ext;
            $dest = '../../../storages/layanan/'.$newName;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $gambar = $newName;
            } else $err = "Gagal upload gambar.";
        }
    }

    if (!$err) {
        $adminID = $_SESSION['id_admin'] ?? null;
        $q = "INSERT INTO pelayanan (judul, deskripsi, gambar, status, dibuat_oleh) VALUES ('$judul','$deskripsi','$gambar','$status', ".($adminID?intval($adminID):'NULL').")";
        if (mysqli_query($connect, $q)) {
            header("Location: index.php?success=added");
            exit;
        } else {
            $err = "Gagal menyimpan data: ".mysqli_error($connect);
        }
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
          <h4 class="mb-4"><i class="bx bx-plus-circle me-2"></i> Tambah Pelayanan</h4>

          <?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>

          <div class="card p-4">
            <form method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label class="form-label">Judul</label>
                <input name="judul" class="form-control" required value="<?= isset($_POST['judul'])?htmlspecialchars($_POST['judul']):'' ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="5" required><?= isset($_POST['deskripsi'])?htmlspecialchars($_POST['deskripsi']):'' ?></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" name="gambar" accept="image/*" class="form-control" onchange="previewImg(event)">
                <img id="imgPreview" src="#" style="display:none;width:150px;height:100px;object-fit:cover;margin-top:10px;" class="rounded shadow-sm">
              </div>

              <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                  <option value="Aktif">Aktif</option>
                  <option value="Nonaktif">Nonaktif</option>
                </select>
              </div>

              <button class="btn btn-primary"><i class="bx bx-save"></i> Simpan</button>
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
