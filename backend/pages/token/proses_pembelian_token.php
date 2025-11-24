<?php
// Mulai session untuk mengambil data user yang login
session_start();

// Include koneksi database Anda
include 'koneksi.php'; 

// Cek apakah pelanggan sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    // Ganti path jika berbeda
    header("Location: ../../pages/auth/login.php?msg=Anda harus login untuk membeli token."); 
    exit;
}

// ==========================================================
// 1. AMBIL DAN VALIDASI DATA
// ==========================================================

// Lokasi penyimpanan file (Relatif terhadap folder file ini)
$uploadDirectory = '../../storages/bukti/'; 
$bukti_transfer_name = NULL; 

$id_pelanggan = $_SESSION['id_pelanggan'];
$kwh_number = $_POST['kwh_number'] ?? '';
$nominal = intval($_POST['nominal'] ?? 0);
$total_bayar = intval($_POST['total_bayar'] ?? 0);
$kwh_didapat = floatval($_POST['kwh_didapat'] ?? 0.00); // KWH didapat
$tipe_bayar = $_POST['tipeBayar'] ?? ''; // Bank, E-Wallet, atau Cash
$metode_bayar = $_POST['metodeBayar'] ?? ''; 

// Validasi dasar
if (empty($kwh_number) || $nominal <= 0 || $total_bayar <= 0 || empty($tipe_bayar)) {
    die("Data transaksi tidak lengkap atau tidak valid.");
}

// Tentukan status awal transaksi
if ($tipe_bayar === 'Cash') {
    $status_pembayaran = 'Pending Cash';
} else {
    $status_pembayaran = 'Menunggu Verifikasi';
}

// ==========================================================
// 2. LOGIKA UPLOAD BUKTI TRANSFER
// ==========================================================
if ($tipe_bayar === 'Bank' || $tipe_bayar === 'E-Wallet') {
    if (isset($_FILES['buktiTransfer']) && $_FILES['buktiTransfer']['error'] === UPLOAD_ERR_OK) {
        
        $file_tmp = $_FILES['buktiTransfer']['tmp_name'];
        
        // Buat nama file baru berdasarkan timestamp dan paksa ekstensi menjadi .png
        $timestamp = time(); 
        $bukti_transfer_name = $timestamp . '.png';
        $target_file = $uploadDirectory . $bukti_transfer_name;

        // Pindahkan file
        if (!move_uploaded_file($file_tmp, $target_file)) {
            die("Gagal mengupload bukti transfer. Mohon coba lagi.");
        }
        
    } else {
        // Jika Bank/E-Wallet dipilih tapi file tidak ada/gagal upload
        die("Bukti transfer wajib diupload untuk metode Bank/E-Wallet.");
    }
}

// ==========================================================
// 3. SIMPAN DATA TRANSAKSI KE DATABASE (Tabel pembayaran)
// ==========================================================

// Data untuk disimpan
$tanggal_pembayaran = date('Y-m-d H:i:s');
$bulan_ini = date('m');
$biaya_admin = 2000; // Sesuai dengan konstanta JS

// Kolom id_tagihan diisi NULL karena ini adalah pembelian token prabayar
// Kolom id_admin diisi NULL karena belum ada admin yang memverifikasi
$null_tagihan = NULL; 
$null_admin = NULL; 
$tipe_metode = $tipe_bayar . ($metode_bayar ? ' (' . $metode_bayar . ')' : '');

// Query Insert
// Pastikan urutan kolom sesuai dengan tabel 'pembayaran' di database Anda
// Asumsi: (id_tagihan, id_pelanggan, tanggal_pembayaran, bulan_bayar, biaya_admin, total_bayar, id_admin, metode, bukti, status)
$qInsert = "INSERT INTO pembayaran 
            (id_tagihan, id_pelanggan, tanggal_pembayaran, bulan_bayar, biaya_admin, total_bayar, id_admin, metode, bukti, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connect, $qInsert);

// Tipe data: (i: integer, i: integer, s: string, s: string, d: double/float, i: integer, i: integer, s: string, s: string, s: string)
// Sesuaikan jika tipe data di DB berbeda
mysqli_stmt_bind_param($stmt, "iissdissss", 
    $null_tagihan,      // id_tagihan (NULL)
    $id_pelanggan,      // id_pelanggan
    $tanggal_pembayaran,// tanggal_pembayaran
    $bulan_ini,         // bulan_bayar
    $biaya_admin,       // biaya_admin
    $total_bayar,       // total_bayar
    $null_admin,        // id_admin (NULL)
    $tipe_metode,       // metode
    $bukti_transfer_name,// bukti
    $status_pembayaran  // status
);

if (mysqli_stmt_execute($stmt)) {
    // Transaksi berhasil disimpan
    $id_pembayaran_baru = mysqli_insert_id($connect);
    
    // Simpan juga detail token ke tabel lain (misal: 'token_prabayar')
    // Ini adalah langkah tambahan yang disarankan:
    /*
    $qToken = "INSERT INTO token_prabayar (id_pembayaran, nomor_kwh, nominal, kwh_didapat, token_string, status_token)
               VALUES (?, ?, ?, ?, NULL, 'Menunggu Token')";
    // ... jalankan query token ...
    */
    
    // Redirect ke halaman status/konfirmasi
    header("Location: status_pembelian.php?id_pembayaran=" . $id_pembayaran_baru);
    exit;

} else {
    // Gagal menyimpan ke database
    die("Error: Gagal menyimpan transaksi ke database. " . mysqli_error($connect));
}

mysqli_stmt_close($stmt);
mysqli_close($connect);

?>