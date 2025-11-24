<?php
// Pastikan file partials/header.php dan partials/navbar.php ada
include '../partials/header.php';
include '../partials/navbar.php';

// Pastikan Anda telah mendefinisikan koneksi database jika diperlukan untuk operasi real-time di masa depan.
// Misal: include 'koneksi.php'; 

// --- BAGIAN UTAMA: FORM PEMBELIAN TOKEN ---
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Beli Token Listrik Prabayar</h4>
                </div>
                <div class="card-body p-4">

                    <form id="formBeliToken">
                        <div class="mb-3">
                            <label for="nomorKwh" class="form-label fw-bold">Nomor Meter / ID Pelanggan</label>
                            <input type="text" class="form-control" id="nomorKwh" name="nomorKwh" placeholder="Masukkan 11-12 digit Nomor Meter KWH" required>
                        </div>

                        <div class="mb-3">
                            <label for="nominalToken" class="form-label fw-bold">Pilih Nominal Pembelian (Rp)</label>
                            <select class="form-select" id="nominalToken" name="nominalToken" required>
                                <option value="">--- Pilih Harga Token ---</option>
                                <option value="5000">5.000</option>
                                <option value="10000">10.000</option>
                                <option value="20000">20.000</option>
                                <option value="50000">50.000</option>
                                <option value="100000">100.000</option>
                                <option value="250000">250.000</option>
                                <option value="500000">500.000</option>
                                <option value="1000000">1.000.000</option>
                            </select>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="button" class="btn btn-info rounded-pill text-white" id="btnHitung">
                                <i class="fa fa-calculator me-2"></i> Hitung KWH
                            </button>
                        </div>
                    </form>

                    <div id="hasilKalkulator" class="border p-3 rounded-3 bg-light d-none">
                        <h6 class="fw-bold text-success border-bottom pb-2">Hasil Perhitungan:</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td>Nominal Pembelian</td>
                                <td class="text-end" id="nominalDisplay">Rp 0</td>
                            </tr>
                            <tr>
                                <td>Biaya Admin</td>
                                <td class="text-end" id="biayaAdminDisplay">Rp 2.000</td>
                            </tr>
                            <tr>
                                <td>**Total Pembayaran**</td>
                                <td class="text-end fw-bold text-danger" id="totalBayarDisplay">Rp 0</td>
                            </tr>
                            <tr>
                                <td>**Estimasi KWH Didapat**</td>
                                <td class="text-end fw-bold text-primary" id="kwhDidapatDisplay">0 KWH</td>
                            </tr>
                        </table>

                        <div class="d-grid mt-3">
                            <button class="btn btn-success rounded-pill" data-bs-toggle="modal" data-bs-target="#modalPembayaran" disabled id="btnLanjutBayar">
                                Lanjutkan Pembayaran
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPembayaran" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Langkah Pembayaran Token Listrik</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="proses_pembelian_token.php" method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="kwh_number" id="hiddenKwhNumber">
                    <input type="hidden" name="nominal" id="hiddenNominal">
                    <input type="hidden" name="total_bayar" id="hiddenTotalBayar">
                    <input type="hidden" name="kwh_didapat" id="hiddenKwhDidapat">

                    <div class="alert alert-warning py-2 small text-center mb-4">
                        Total yang harus dibayar: <strong id="finalTotalDisplay" class="fs-5">Rp 0</strong>
                    </div>

                    <h6 class="fw-bold mb-3">1. Pilih Tipe Pembayaran</h6>
                    <div class="row g-2 mb-4 text-center">
                        <div class="col-4">
                            <input type="radio" class="btn-check" name="tipeBayar" id="tipeBank" value="Bank" required>
                            <label class="btn btn-outline-primary w-100 rounded-pill" for="tipeBank"><i class="fa fa-university me-1"></i> Bank Transfer</label>
                        </div>
                        <div class="col-4">
                            <input type="radio" class="btn-check" name="tipeBayar" id="tipeEwallet" value="E-Wallet" required>
                            <label class="btn btn-outline-primary w-100 rounded-pill" for="tipeEwallet"><i class="fa fa-mobile-alt me-1"></i> E-Wallet</label>
                        </div>
                        <div class="col-4">
                            <input type="radio" class="btn-check" name="tipeBayar" id="tipeCash" value="Cash" required>
                            <label class="btn btn-outline-primary w-100 rounded-pill" for="tipeCash"><i class="fa fa-cash-register me-1"></i> Tunai (Cash)</label>
                        </div>
                    </div>

                    <div id="opsiTransfer" class="d-none p-3 border rounded-3">
                        <h6 class="fw-bold mb-3">2. Detail Metode & Bukti Transfer</h6>

                        <div class="mb-3">
                            <label for="metodeBayar" class="form-label">Pilih Detail Bank / E-Payment</label>
                            <select class="form-select" id="metodeBayar" name="metodeBayar">
                                <option value="">--- Pilih Detail Transfer ---</option>
                                <option value="BCA">Transfer BCA 5220504123 (a.n. PLN)</option>
                                <option value="Mandiri">Transfer Mandiri 1234567890 (a.n. PLN)</option>
                                <option value="BRI">Transfer BRI 9876543210 (a.n. PLN)</option>
                                <option value="BNI">Transfer BNI 1122334455 (a.n. PLN)</option>
                                <option value="Dana">E-Wallet (Dana) 0812xxxxxx</option>
                                <option value="Gopay">E-Wallet (Gopay) 0813xxxxxx</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="buktiTransfer" class="form-label">Upload Bukti Transfer (Bank/E-Wallet)</label>
                            <input class="form-control" type="file" id="buktiTransfer" name="buktiTransfer" accept="image/*, .pdf">
                            <div class="form-text">Wajib diisi untuk verifikasi. Maksimal 2MB. Format: JPG, PNG, atau PDF.</div>
                        </div>
                    </div>

                    <div id="opsiCash" class="d-none p-3 border rounded-3 bg-light">
                        <h6 class="fw-bold text-danger">Pembayaran Tunai (Cash)</h6>
                        <p class="text-secondary small mb-0">Pembayaran akan dilakukan secara langsung di konter/kantor PPOB kami. Setelah menekan 'Bayar Sekarang', status akan menjadi **'Pending Cash'** dan Anda harus menyelesaikan pembayaran secara fisik.</p>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success rounded-pill">
                            Bayar Sekarang
                        </button>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-center bg-light small" id="konfirmasiPesan">
                <i class="fa fa-info-circle text-primary me-2"></i> Pembelian berhasil! Silakan tunggu konfirmasi Admin. Token akan dikirim setelah verifikasi pembayaran sukses.
            </div>

        </div>
    </div>
</div>

<script>
    // **KONSTANTA GLOBAL**
    const tarifperkwh = 1352.00;
    const biaya_admin = 2000;

    // Fungsi untuk format Rupiah
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // 1. LOGIKA KALKULATOR
    document.getElementById('btnHitung').addEventListener('click', function() {
        const nominal = document.getElementById('nominalToken').value;
        const nomorKwh = document.getElementById('nomorKwh').value;
        const hasilDiv = document.getElementById('hasilKalkulator');
        const btnLanjut = document.getElementById('btnLanjutBayar');

        if (!nominal || nomorKwh.length < 11) {
            alert('Mohon lengkapi Nomor KWH (minimal 11 digit) dan Pilih Nominal Token.');
            hasilDiv.classList.add('d-none');
            btnLanjut.disabled = true;
            return;
        }

        const nominalAngka = parseInt(nominal);
        const totalBayar = nominalAngka + biaya_admin;
        const kwhDidapat = nominalAngka / tarifperkwh;

        // Tampilkan Hasil di Kalkulator
        document.getElementById('nominalDisplay').innerText = formatRupiah(nominalAngka);
        document.getElementById('biayaAdminDisplay').innerText = formatRupiah(biaya_admin);
        document.getElementById('totalBayarDisplay').innerText = formatRupiah(totalBayar);
        document.getElementById('kwhDidapatDisplay').innerText = kwhDidapat.toFixed(2) + ' KWH';

        hasilDiv.classList.remove('d-none');
        btnLanjut.disabled = false;

        // Update data ke hidden fields dan modal display
        document.getElementById('hiddenKwhNumber').value = nomorKwh;
        document.getElementById('hiddenNominal').value = nominal;
        document.getElementById('hiddenTotalBayar').value = totalBayar;
        document.getElementById('hiddenKwhDidapat').value = kwhDidapat.toFixed(2); // Simpan nilai KWH yang didapat
        document.getElementById('finalTotalDisplay').innerText = formatRupiah(totalBayar);
    });


    // 2. LOGIKA MODAL PEMBAYARAN DINAMIS
    const radioBank = document.getElementById('tipeBank');
    const radioEwallet = document.getElementById('tipeEwallet');
    const radioCash = document.getElementById('tipeCash');

    const opsiTransferDiv = document.getElementById('opsiTransfer');
    const opsiCashDiv = document.getElementById('opsiCash');
    const metodeBayarSelect = document.getElementById('metodeBayar');
    const buktiTransferInput = document.getElementById('buktiTransfer');

    // Event Listener untuk Radio Buttons
    [radioBank, radioEwallet, radioCash].forEach(radio => {
        radio.addEventListener('change', function() {
            // 1. Reset visibility
            opsiTransferDiv.classList.add('d-none');
            opsiCashDiv.classList.add('d-none');

            // 2. Reset requirements
            metodeBayarSelect.removeAttribute('required');
            buktiTransferInput.removeAttribute('required');
            metodeBayarSelect.value = "";

            if (this.value === 'Bank' || this.value === 'E-Wallet') {
                // Tampilkan opsi transfer/ewallet dan wajibkan isian
                opsiTransferDiv.classList.remove('d-none');
                metodeBayarSelect.setAttribute('required', 'required');
                buktiTransferInput.setAttribute('required', 'required');

            } else if (this.value === 'Cash') {
                // Tampilkan opsi cash
                opsiCashDiv.classList.remove('d-none');
            }
        });
    });

    // Reset conditions when the modal is fully closed
    document.getElementById('modalPembayaran').addEventListener('hidden.bs.modal', function() {
        radioBank.checked = false;
        radioEwallet.checked = false;
        radioCash.checked = false;
    });
</script>

<?php
// Pastikan file partials/footer.php dan partials/script.php ada
include '../partials/footer.php';
include '../partials/script.php';
?>