<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">

    <div class="layout-menu-toggle d-xl-none ms-3">
        <a class="nav-link px-0" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <!-- Search -->
        <div class="navbar-nav align-items-center me-auto d-none d-md-block">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 me-1"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Cari...">
            </div>
        </div>

        <!-- User -->
        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- Avatar Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="../../sneat-1.0.0/assets/img/avatars/1.png"
                            class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="../admin/index.php">
                            <i class="bx bx-user me-2"></i>
                            <span>Profil Saya</span>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-cog me-2"></i>
                            <span>Pengaturan</span>
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item text-danger" href="../auth/logout.php">
                            <i class="bx bx-power-off me-2"></i>
                            <span>Keluar</span>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</nav>