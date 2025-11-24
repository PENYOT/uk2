<?php
include '../../partials/header.php';
include '../../../config/connection.php';

$id = intval($_GET['id']);
$q = mysqli_query($connect, "SELECT * FROM promo WHERE id_promo=$id");
$data = mysqli_fetch_object($q);

if (!$data) { header("Location:index.php"); exit; }

$err = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $diskon = intval($_POST['diskon']);
    $mulai = $_POST['tanggal_mulai'];
    $selesai = $_POST['tanggal_selesai'];
    $status = $_POST['status'];
    $gambar = $data->gambar;

    // Jika upload gambar baru
    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newName = "promo_" . time() . rand(1000,9999) . "." . $ext;

        if (move_uploaded_file($file['tmp_name'], "../../../storages/promo/" . $newName)) {

            // hapus gambar lama kecuali default
            if ($gambar !== 'default.jpg') {
                @unlink("../../../storages/promo/" . $gambar);
            }

            $gambar = $newName;
        }
    }

    $adminID = $_SESSION['id_admin'] ?? 1;

    $update = "
        UPDATE promo SET 
            judul='$judul',
            deskripsi='$deskripsi',
            gambar='$gambar',
            diskon='$diskon',
            tanggal_mulai='$mulai',
            tanggal_selesai='$selesai',
            status='$status',
            diupdate_oleh='$adminID'
        WHERE id_promo=$id
    ";

    if (mysqli_query($connect, $update)) {
        $success = "Promo berhasil diperbarui!";
    } else {
        $err = "Gagal mengupdate promo!";
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

    <h4 class="fw-bold mb-4"><i class="bx bx-edit me-2"></i> Edit Promo</h4>

    <?php if ($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <div class="card shadow-sm p-4 rounded-4">
        <form method="POST" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-8">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul</label>
                        <input type="text" name="judul" class="form-control" value="<?= $data->judul ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4"><?= $data->deskripsi ?></textarea>
                    </div>

                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Gambar Sekarang</label><br>
                    <img src="../../../storages/promo/<?= $data->gambar ?>" class="rounded shadow-sm" style="width:180px;height:120px;object-fit:cover">

                    <div class="mt-3">
                        <label class="form-label fw-semibold">Ganti Gambar</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Diskon (%)</label>
                    <input type="number" name="diskon" value="<?= $data->diskon ?>" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="<?= $data->tanggal_mulai ?>" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="<?= $data->tanggal_selesai ?>" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option <?= $data->status=='Aktif'?'selected':'' ?>>Aktif</option>
                    <option <?= $data->status=='Nonaktif'?'selected':'' ?>>Nonaktif</option>
                </select>
            </div>

            <button class="btn btn-primary"><i class="bx bx-save"></i> Perbarui</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>

        </form>
    </div>

</div>
</div>

<?php include '../../partials/footer.php'; ?>
</div>
</div>
</div>

<?php include '../../partials/script.php'; ?>
