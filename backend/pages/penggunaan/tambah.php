<?php
include '../../partials/header.php';
include '../../../config/connection.php';

// Ambil semua pelanggan
$qPelanggan = mysqli_query($connect, "SELECT id_pelanggan, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan ASC");
?>

<div class="layout-wrapper layout-content-navbar">
<div class="layout-container">

<?php include '../../partials/sidebar.php'; ?>

<div class="layout-page">
<?php include '../../partials/navbar.php'; ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold mb-4"><i class="bx bx-bolt"></i> Tambah Data Penggunaan Listrik</h4>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            <form action="proses_tambah.php" method="POST" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pelanggan</label>
                    <select name="id_pelanggan" class="form-select" required>
                        <option value="">-- Pilih Pelanggan --</option>

                        <?php while ($p = mysqli_fetch_assoc($qPelanggan)) : ?>
                            <option value="<?= $p['id_pelanggan'] ?>">
                                <?= htmlspecialchars($p['nama_pelanggan']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Bulan</label>
                    <select name="bulan" class="form-select" required>
                        <?php
                        $bulanList = [
                            'January','February','March','April','May','June',
                            'July','August','September','October','November','December'
                        ];
                        foreach ($bulanList as $b) {
                            echo "<option value='$b'>$b</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="<?= date('Y') ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Meter Awal</label>
                    <input type="number" name="meter_awal" id="meter_awal" class="form-control" required oninput="hitungTotal()">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Meter Akhir</label>
                    <input type="number" name="meter_ahir" id="meter_ahir" class="form-control" required oninput="hitungTotal()">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Total Pemakaian</label>
                    <input type="text" id="total_meter" class="form-control bg-light" readonly>
                </div>

                <div class="col-12 mt-3">
                    <button type="submit" name="simpan" class="btn btn-warning">
                        <i class="bx bx-save"></i> Simpan Penggunaan
                    </button>
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

<script>
function hitungTotal() {
    let awal = parseInt(document.getElementById("meter_awal").value) || 0;
    let akhir = parseInt(document.getElementById("meter_ahir").value) || 0;

    if (akhir >= awal) {
        document.getElementById("total_meter").value = akhir - awal;
    } else {
        document.getElementById("total_meter").value = "Error";
    }
}
</script>
