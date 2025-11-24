<?php
// Ambil URL saat ini untuk highlight menu aktif
$current = $_SERVER["REQUEST_URI"];

// Helper function untuk menentukan menu aktif
function isActive($keyword, $current)
{
    return (strpos($current, $keyword) !== false) ? 'active' : '';
}

function isOpen($keyword, $current)
{
    return (strpos($current, $keyword) !== false) ? 'open active' : '';
}
?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- LOGO -->
    <div class="app-brand demo">
        <a href="../dashboard/index.php" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="../../../storages/1.png" class="w-px-40" />
            </span>
            <span class="app-brand-text demo fw-bolder ms-2 text-uppercase">Sistem Listrik</span>
        </a>

        <a href="#" class="layout-menu-toggle menu-link ms-auto d-xl-none">
            <i class="bx bx-chevron-left bx-sm"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- DASHBOARD -->
        <li class="menu-item <?= isActive('dashboard', $current) ?>">
            <a href="../dashboard/index.php" class="menu-link">
                <i class="menu-icon bx bx-home-circle"></i>
                <div>Beranda</div>
            </a>
        </li>

        <!-- SECTION HEADER -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Manajemen Data</span>
        </li>

        <!-- PELANGGAN -->
        <li class="menu-item <?= isActive('pelanggan', $current) ?>">
            <a href="../pelanggan/index.php" class="menu-link">
                <i class="menu-icon bx bx-user"></i>
                <div>Pelanggan</div>
            </a>
        </li>

        <!-- PENGGUNAAN -->
        <li class="menu-item <?= isActive('penggunaan', $current) ?>">
            <a href="../penggunaan/index.php" class="menu-link">
                <i class="menu-icon bx bx-bolt-circle"></i>
                <div>Penggunaan</div>
            </a>
        </li>

        <!-- TAGIHAN -->
        <li class="menu-item <?= isActive('tagihan', $current) ?>">
            <a href="../tagihan/index.php" class="menu-link">
                <i class="menu-icon bx bx-file"></i>
                <div>Tagihan</div>
            </a>
        </li>

        <!-- PEMBAYARAN -->
        <li class="menu-item <?= isActive('pembayaran', $current) ?>">
            <a href="../pembayaran/index.php" class="menu-link">
                <i class="menu-icon bx bx-wallet"></i>
                <div>Pembayaran</div>
            </a>
        </li>

        <!-- TARIF -->
        <li class="menu-item <?= isActive('tarif', $current) ?>">
            <a href="../tarif/index.php" class="menu-link">
                <i class="menu-icon bx bx-bar-chart-alt"></i>
                <div>Tarif</div>
            </a>
        </li>

        <!-- AKUN & PENGATURAN -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Akun & Pengaturan</span>
        </li>

        <li class="menu-item <?= isOpen('admin', $current) ?>">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon bx bx-user-circle"></i>
                <div>Pengaturan Akun</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item <?= isActive('admin/index', $current) ?>">
                    <a href="../admin/index.php" class="menu-link">
                        <div>Data Akun</div>
                    </a>
                </li>

                <li class="menu-item <?= isActive('register', $current) ?>">
                    <a href="../auth/register.php" class="menu-link">
                        <div>Tambah Admin</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- FITUR TAMBAHAN -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Fitur Tambahan</span>
        </li>

        <li class="menu-item <?= isActive('promo', $current) ?>">
            <a href="../promo/index.php" class="menu-link">
                <i class="menu-icon bx bx-gift"></i>
                <div>Promo</div>
            </a>
        </li>

        <li class="menu-item <?= isActive('layanan', $current) ?>">
            <a href="../layanan/index.php" class="menu-link">
                <i class="menu-icon bx bx-support"></i>
                <div>Layanan</div>
            </a>
        </li>

        <!-- LOGOUT BUTTON -->
        <li class="menu-item mt-3">
            <a href="../auth/logout.php" class="menu-link text-danger">
                <i class="menu-icon bx bx-power-off text-danger"></i>
                <div>Logout</div>
            </a>
        </li>

    </ul>
</aside>