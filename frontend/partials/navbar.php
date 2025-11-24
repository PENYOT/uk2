<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CEK LOGIN SESUAI login_action
$isLoggedIn = isset($_SESSION['id_pelanggan']);

$pelanggan = $isLoggedIn ? [
    'id'    => $_SESSION['id_pelanggan'],
    'nama'  => $_SESSION['name'],
    'email' => $_SESSION['email'],
    'image' => $_SESSION['image'] ?? null
] : null;

$nama_pelanggan = $pelanggan['nama'] ?? 'Pengguna';
$foto = $pelanggan['image'] ?? null;

// Path foto profil
$basePath = realpath(__DIR__ . '../../storages/pelanggan/');

if ($foto && file_exists($basePath . "/" . $foto)) {
    $fotoUrl = "../../../storages/pelanggan/" . $foto;
} else {
    $fotoUrl = "../assets/img/default-user.png";
}
?>

<nav class="navbar navbar-expand-lg bg-white navbar-light shadow border-top border-5 border-primary sticky-top p-0">

    <a href="index.php" class="navbar-brand bg-primary d-flex align-items-center px-4 px-lg-5">
        <h2 class="mb-2 text-white">E-Payment Listrik</h2>
    </a>

    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">

        <div class="navbar-nav ms-auto p-4 p-lg-0">

            <a href="index.php" class="nav-item nav-link">Beranda</a>
            <!-- <a href="about.php" class="nav-item nav-link">Tentang</a> -->

            <!-- Dropdown Layanan -->
            <div class="nav-item dropdown">

                <a href="#" 
                   class="nav-link dropdown-toggle <?= !$isLoggedIn ? 'text-secondary' : '' ?>" 
                   data-bs-toggle="dropdown"
                   style="<?= !$isLoggedIn ? 'pointer-events:none;opacity:0.5;' : '' ?>">
                    Layanan
                </a>

                <div class="dropdown-menu fade-up m-0">
                    <?php if ($isLoggedIn): ?>
                        <a href="../pages/detail_tagihan.php" class="dropdown-item">Pembayaran Listrik</a>
                        <a href="../pages/riwayat_pembayaran.php" class="dropdown-item">Riwayat Pembayaran</a>
                        <a href="../pages/token.php" class="dropdown-item">Pembelian Token</a>
                    <?php else: ?>
                        <span class="dropdown-item text-muted">Login untuk mengakses layanan</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- <a href="kontak.php" class="nav-item nav-link">Kontak</a> -->

            <!-- Menu Profil / Login -->
            <?php if ($isLoggedIn): ?>

                <li class="nav-item dropdown ms-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                        <img src="<?= $fotoUrl ?>" 
                             style="width:35px;height:35px;border-radius:50%;object-fit:cover;">
                        <span class="fw-bold ms-2"><?= htmlspecialchars($nama_pelanggan); ?></span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <!-- <li><a class="dropdown-item" href="../pages/profile.php">Profil Saya</a></li> -->
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../../backend/pages/auth/logout.php">Logout</a></li>
                    </ul>
                </li>

            <?php else: ?>

                <li class="nav-item"><a href="../../backend/pages/auth/login.php" class="nav-link">Login</a></li>
                <li class="nav-item"><a href="../../backend/pages/auth/register.php" class="nav-link">Daftar</a></li>

            <?php endif; ?>

        </div>
    </div>
</nav>
