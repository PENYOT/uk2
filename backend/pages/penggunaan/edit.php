<?php
// edit.php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../../partials/header.php';
$id = isset($_GET['id_penggunaan']) ? (int)$_GET['id_penggunaan'] : 0;
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// ambil data
$q = "SELECT * FROM penggunaan WHERE id_penggunaan = ?";
$stmt = mysqli_prepare($connect, $q);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: index.php");
    exit;
}

// ambil list pelanggan
$pelangganRes = mysqli_query($connect, "SELECT id_pelanggan, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan ASC");

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pelanggan = (int)$_POST['id_pelanggan'];
    $bulan        = mysqli_real_escape_string($connect, trim($_POST['bulan']));
    $tahun        = (int)$_POST['tahun'];
    $meter_awal   = (int)$_POST['meter_awal'];
    $meter_ahir   = (int)$_POST['meter_ahir'];

    if ($meter_ahir < $meter_awal) $errors[] = "Meter akhir harus >= meter awal.";

    if (empty($errors)) {
        $uq = "UPDATE penggunaan SET id_pelanggan=?, bulan=?, tahun=?, meter_awal=?, meter_ahir=? WHERE id_penggunaan=?";
        $ust = mysqli_prepare($connect, $uq);
        mysqli_stmt_bind_param($ust, "issiii", $id_pelanggan, $bulan, $tahun, $meter_awal, $meter_ahir, $id);
        if (mysqli_stmt_execute($ust)) {
            mysqli_stmt_close($ust);
            header("Location: index.php?success=updated");
            exit;
        } else {
            $errors[] = "Gagal update: " . mysqli_error($connect);
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
        <div class="container-xxl">
          <div class="card">
            <div class="card-header bg-primary text-white fw-semibold">Edit Penggunaan</div>
            <div class="card-body">
              <?php if ($errors): ?>
                <div class="alert alert-danger">
                  <ul class="mb-0"><?php foreach ($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
                </div>
              <?php endif; ?>

              <form method="POST" class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Pelanggan</label>
                  <select name="id_pelanggan" class="form-select" required>
                    <?php while ($p = mysqli_fetch_assoc($pelangganRes)): ?>
                      <option value="<?= $p['id_pelanggan'] ?>" <?= ($p['id_pelanggan']==$data['id_pelanggan'])? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nama_pelanggan']) ?>
                      </option>
                    <?php endwhile; ?>
                  </select>
                </div>

                <div class="col-md-3">
                  <label class="form-label">Bulan</label>
                  <input name="bulan" class="form-control" value="<?= htmlspecialchars($data['bulan']) ?>" required>
                </div>

                <div class="col-md-3">
                  <label class="form-label">Tahun</label>
                  <input type="number" name="tahun" min="2000" max="<?= date('Y')+1 ?>" class="form-control" value="<?= $data['tahun'] ?>" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Meter Awal</label>
                  <input type="number" name="meter_awal" class="form-control" value="<?= $data['meter_awal'] ?>" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Meter Akhir</label>
                  <input type="number" name="meter_ahir" class="form-control" value="<?= $data['meter_ahir'] ?>" required>
                </div>

                <div class="col-12">
                  <button class="btn btn-success"><i class="bx bx-save me-1"></i> Update</button>
                  <a href="index.php" class="btn btn-secondary">Kembali</a>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>

      <?php include '../../partials/footer.php'; ?>
    </div>
  </div>
</div>

<?php include '../../partials/script.php'; ?>
