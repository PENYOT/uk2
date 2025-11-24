# TODO: Perbaikan Kode Backend Aplikasi Pembayaran Listrik

## Masalah yang Ditemukan dan Perbaikan

### 1. login_action.php

- [x] Perbaiki spasi ekstra di `$_SESSION['id_pelanggan ']` menjadi `$_SESSION['id_pelanggan']`
- [ ] Periksa konsistensi nama kolom database (phone vs no_telepon)

### 2. login.php

- [x] Pindahkan `session_start()` ke awal file, hapus duplikasi
- [x] Perbaiki logika session check

### 3. logout.php

- [x] Hapus komentar yang salah

### 4. register_action.php

- [x] Tambahkan validasi unik untuk nomor_kwh dan username/email
- [x] Perbaiki validasi input kosong

### 5. pelanggan/index.php

- [ ] Konsistensikan nama kolom `phone` vs `no_telepon`
- [x] Perbaiki session_start() yang di dalam if

### 6. dashboard/index.php

- [x] Hapus duplikasi include navbar

### 7. sidebar.php

- [x] Perbaiki struktur HTML yang salah (tag </li> yang tidak cocok)

### 8. navbar.php

- [x] Bersihkan style yang tidak perlu atau pindahkan ke CSS terpisah

### 9. footer.php

- [x] Perbaiki struktur HTML yang tidak lengkap

### 10. script.php

- [x] Perbaiki tag body yang tidak lengkap

### 11. header.php

- [ ] Evaluasi include app.php, mungkin tidak perlu

### 12. app.php

- [x] Perbaiki path include config

### 13. connection.php

- [x] Perbaiki indentasi

### 14. escapeString.php

- [ ] Pastikan fungsi digunakan di tempat yang tepat atau hapus jika tidak perlu

### 15. Umum

- [ ] Periksa konsistensi path relatif di seluruh aplikasi
- [ ] Pastikan semua file menggunakan session dengan benar
- [ ] Validasi input di semua form
- [ ] Periksa keamanan (SQL injection, XSS, dll.)
