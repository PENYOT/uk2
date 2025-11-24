<?php
include '../../partials/header.php';
include '../../../config/connection.php';

$err = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul = mysqli_real_escape_string($connect, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($connect, $_POST['deskripsi']);
    $diskon = intval($_POST['diskon']);
    $mulai = $_POST['tanggal_mulai'];
    $selesai = $_POST['tanggal_selesai'];
    $status = $_POST['status'];

    // ================== UPLOAD GAMBAR ================== //
    $gambar = "default.jpg";
    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (!in_array($ext, $allowed)) {
            $err = "Format gambar tidak didukung.";
        } else {
            $newName = "promo_" . time() . rand(1000,9999) . "." . $ext;
            $uploadPath = "../../../storages/promo/" . $newName;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $gambar = $newName;
            } else {
                $err = "Gagal upload gambar!";
            }
        }
    }

    if (empty($err)) {
        $adminID = $_SESSION['id_admin'] ?? 1;

        $q = "
            INSERT INTO promo (judul, deskripsi, gambar, diskon, tanggal_mulai, tanggal_selesai, status, dibuat_oleh)
            VALUES ('$judul', '$deskripsi', '$gambar', '$diskon', '$mulai', '$selesai', '$status', '$adminID')
        ";

        if (mysqli_query($connect, $q)) {
            $success = "Promo berhasil ditambahkan!";
        } else {
            $err = "Gagal menyimpan promo!";
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

    <h4 class="fw-bold mb-4">
        <i class="bx bx-plus-circle me-2"></i> Tambah Promo
    </h4>

    <?php if ($err): ?><div class="alert alert-danger"><?= $err ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <div class="card shadow-sm p-4 rounded-4">
        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Promo</label>
                <input type="text" name="judul" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="5" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Diskon (%)</label>
                    <input type="number" name="diskon" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" required>
                </div>
            </div>

            <!-- Upload gambar + preview -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Gambar Promo</label>
                <input type="file" name="gambar" class="form-control" accept="image/*" onchange="previewImg(event)">
                <img id="imgPreview" class="mt-3 rounded shadow-sm" style="width:150px; display:none;">
            </div>

            <script>
            function previewImg(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    const img = document.getElementById('imgPreview');
                    img.src = reader.result;
                    img.style.display = "block";
                }
                reader.readAsDataURL(event.target.files[0]);
            }
            </script>

            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option>Aktif</option>
                    <option>Nonaktif</option>
                </select>
            </div>

            <button class="btn btn-primary"><i class="bx bx-save"></i> Simpan</button>
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
