<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $title ?? 'Admin Panel - Barber POS' ?>
    </title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
    :root {
        --sidebar-width: 260px;
        --primary-dark: #111111;
        --accent-gold: #d4af37;
        --bg-body: #f4f7f6;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-body);
        overflow-x: hidden;
    }

    /* Sidebar Styling */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: var(--sidebar-width);
        background: var(--primary-dark);
        color: #fff;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .sidebar-brand {
        padding: 25px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .sidebar-brand .logo-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--accent-gold) 0%, #aa771c 100%);
        color: #111;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 20px;
        font-weight: bold;
    }

    .sidebar-menu {
        padding: 20px 15px 20px 24px;
        /* <-- Menu digeser sedikit ke kanan */
        list-style: none;
        margin: 0;
        flex-grow: 1;
        overflow-y: auto;
    }

    .sidebar-category {
        font-size: 11px;
        letter-spacing: 0.8px;
        font-weight: 700;
        color: #6c757d;
        padding: 12px 15px 6px 5px;
        margin-top: 10px;
        text-transform: uppercase;
    }

    .sidebar-menu li {
        margin-bottom: 8px;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        color: #a0a0a0;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
        background: rgba(212, 175, 55, 0.12);
        color: var(--accent-gold);
    }

    .sidebar-menu a i {
        font-size: 18px;
    }

    /* Main Wrapper */
    .main-wrapper {
        margin-left: var(--sidebar-width);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Navbar Top */
    .navbar-top {
        background: #ffffff;
        height: 70px;
        padding: 0 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        position: sticky;
        top: 0;
        z-index: 9999 !important;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #111111 0%, #333333 100%);
        color: var(--accent-gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Content Area */
    .content-body {
        padding: 30px;
        flex-grow: 1;
    }

    /* Dropdown Profil di Pojok Kanan Atas */
    .user-profile-dropdown {
        position: relative !important;
    }

    .user-profile-dropdown .dropdown-toggle::after {
        display: none;
    }

    .user-profile-dropdown .dropdown-menu {
        position: absolute !important;
        right: 0 !important;
        left: auto !important;
        top: 5% !important;
        margin-top: 2px !important;
        width: 320px !important;
        padding: 20px !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid #eaeaea !important;
        z-index: 2147483647 !important;
    }

    .user-profile-dropdown .dropdown-menu::before {
        content: "";
        position: absolute;
        top: -6px;
        right: 28px;
        width: 12px;
        height: 12px;
        background: #ffffff;
        transform: rotate(45deg);
        border-top: 1px solid #eaeaea;
        border-left: 1px solid #eaeaea;
        z-index: 2147483648 !important;
    }

    /* Modal Styling */
    .modal {
        z-index: 9999999 !important;
    }

    .modal-backdrop {
        z-index: 9999980 !important;
    }

    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
    }

    @media (min-width: 576px) {
        .modal-dialog-centered {
            min-height: calc(100% - 3.5rem);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .main-wrapper {
            margin-left: 0;
        }
    }

    /* Transisi Dropdown & Sub-Menu */
    .menu-toggle .bi-chevron-down {
        transition: transform 0.3s ease;
    }

    .menu-toggle[aria-expanded="true"] .bi-chevron-down {
        transform: rotate(180deg);
    }

    .sidebar-submenu {
        padding-left: 15px;
        margin-top: 2px;
    }

    .sidebar-submenu a {
        padding: 7px 15px 7px 30px !important;
        font-size: 13px;
        color: #bbb !important;
        transition: all 0.2s ease-in-out;
    }

    .sidebar-submenu a:hover,
    .sidebar-submenu a.active {
        color: #ffc107 !important;
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
    }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand mb-2">
            <div class="logo-icon">
                <i class="bi bi-scissors text-warning"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-white">Barber POS</h6>
                <small class="text-muted" style="font-size: 11px;">Admin Control Panel</small>
            </div>
        </div>

        <ul class="sidebar-menu list-unstyled px-2">

            <!-- MENU UTAMA -->
            <div class="sidebar-category">Menu Utama</div>
            <li>
                <a href="<?= base_url('admin/dashboard') ?>"
                    class="text-white text-decoration-none d-flex align-items-center gap-2 py-2 px-3">
                    <i class="bi bi-speedometer2 text-warning"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/pos') ?>"
                    class="text-white text-decoration-none d-flex align-items-center gap-2 py-2 px-3">
                    <i class="bi bi-cash-coin text-warning"></i> Kasir (POS)
                </a>
            </li>

            <!-- MASTER DATA -->
            <div class="sidebar-category">Master Data</div>
            <li>
                <a href="<?= base_url('admin/services') ?>"
                    class="text-white text-decoration-none d-flex align-items-center gap-2 py-2 px-3">
                    <i class="bi bi-grid-fill text-warning"></i> Kelola Layanan
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/products') ?>"
                    class="text-white text-decoration-none d-flex align-items-center gap-2 py-2 px-3">
                    <i class="bi bi-box-seam text-warning"></i> Kelola Produk
                </a>
            </li>

            <!-- TRANSAKSI & LAPORAN -->
            <div class="sidebar-category">Transaksi & Laporan</div>
            <li>
                <a href="<?= base_url('admin/reports/transactions') ?>"
                    class="text-white text-decoration-none d-flex align-items-center gap-2 py-2 px-3">
                    <i class="bi bi-journal-text text-warning"></i>
                    <span>Laporan Transaksi</span>
                </a>
            </li>

            <!-- PENGATURAN / MANAJEMEN PENGGUNA -->
            <div class="sidebar-category">Pengaturan</div>
            <li class="nav-item">
                <a class="nav-link menu-toggle text-white fw-semibold d-flex align-items-center justify-content-between py-2 px-3 rounded-3 text-decoration-none"
                    data-bs-toggle="collapse" href="#userSubMenu" role="button" aria-expanded="false"
                    aria-controls="userSubMenu">
                    <div class="d-flex align-items-center gap-2 text-nowrap">
                        <i class="bi bi-people-fill text-warning"></i>
                        <span>Manajemen Pengguna</span>
                    </div>
                    <i class="bi bi-chevron-down text-warning small ms-auto"></i>
                </a>

                <!-- Sub-Menu Vertikal Tanpa Background -->
                <div class="collapse sidebar-submenu" id="userSubMenu">
                    <ul class="nav flex-column list-unstyled mb-0">
                        <li>
                            <a href="<?= base_url('admin/users') ?>" class="nav-link d-flex align-items-center gap-2">
                                <i class="bi bi-person-lines-fill text-warning"></i> Data User
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/users/roles') ?>"
                                class="nav-link d-flex align-items-center gap-2">
                                <i class="bi bi-shield-lock-fill text-warning"></i> Role Akses
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/users/logs') ?>"
                                class="nav-link d-flex align-items-center gap-2">
                                <i class="bi bi-clock-history text-warning"></i> Logs
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/users/settings') ?>"
                                class="nav-link d-flex align-items-center gap-2">
                                <i class="bi bi-gear-fill text-warning"></i> Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

        </ul>
    </div>

    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">

        <!-- NAVBAR TOP -->
        <div class="navbar-top">
            <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0 fw-bold text-dark">Dashboard Admin</h5>
            </div>

            <!-- PROFILE DROPDOWN MENU  -->
            <div class="dropdown user-profile-dropdown">
                <a href="#" class="d-flex align-items-center gap-3 text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-sm-block">
                        <span class="d-block fw-bold text-dark"
                            style="font-size: 14px;"><?= session()->get('name') ?? 'Administrator' ?></span>
                        <span class="d-block text-muted" style="font-size: 11px;">Owner / Admin</span>
                        <span class="d-block text-secondary" style="font-size: 10px;" id="live-clock">Memuat
                            waktu...</span>
                    </div>
                    <div class="user-avatar">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </a>

                <!-- KARTU MENU DROPDOWN DI POJOK KANAN ATAS -->
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-3">
                    <!-- Kartu Header Profil -->
                    <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
                        <div
                            style="width: 50px; height: 50px; background: rgba(17, 17, 17, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(212, 175, 55, 0.4); flex-shrink: 0;">
                            <i class="bi bi-person-fill text-warning fs-3"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 14px;">
                                <?= session()->get('name') ?? 'Administrator' ?>
                            </h6>
                            <small class="text-muted" style="font-size: 11px;">Owner / Admin</small>
                        </div>
                    </div>

                    <!-- Tombol Menu Sejajar Mendatar (Profil, Kata Sandi, Keluar) -->
                    <div class="d-flex justify-content-between align-items-center pt-1 text-center">
                        <a href="#" class="text-decoration-none text-dark flex-fill" style="font-size: 12px;"
                            data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <div class="mb-1"><i class="bi bi-person fs-5 text-secondary"></i></div>
                            <span class="fw-semibold">Profil</span>
                        </a>
                        <a href="#" class="text-decoration-none text-dark flex-fill border-start border-end"
                            style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                            <div class="mb-1"><i class="bi bi-key fs-5 text-warning"></i></div>
                            <span class="fw-semibold">Kata sandi</span>
                        </a>
                        <a href="<?= base_url('logout') ?>" class="text-decoration-none text-danger flex-fill"
                            style="font-size: 12px;">
                            <div class="mb-1"><i class="bi bi-box-arrow-right fs-5 text-danger"></i></div>
                            <span class="fw-semibold">Keluar</span>
                        </a>
                    </div>
                </ul>
            </div>
        </div>

        <!-- CONTENT BODY -->
        <div class="content-body">
            <?= $this->renderSection('content') ?>
        </div>

    </div>

    <!-- MODAL GANTI PASSWORD ELEGANT & RESPONSIVE -->
    <div class="modal fade" id="editPasswordModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form action="<?= base_url('admin/update-password') ?>" method="post" class="w-100">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                        <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2">
                            <i class="bi bi-shield-lock-fill text-warning"></i> Keamanan Akun
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <p class="text-muted small mb-3">Silakan masukkan password lama dan password baru Anda untuk
                            memperbarui keamanan akun.</p>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Password Lama</label>
                            <input type="password" name="old_password" class="form-control rounded-3 py-2" required
                                placeholder="••••••••">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Password Baru</label>
                            <input type="password" name="new_password" class="form-control rounded-3 py-2" required
                                placeholder="••••••••">
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold small text-secondary">Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" class="form-control rounded-3 py-2" required
                                placeholder="••••••••">
                        </div>
                    </div>
                    <div class="modal-footer bg-white px-4 py-3 border-0">
                        <button type="button" class="btn btn-light rounded-3 px-4 btn-sm fw-semibold"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark rounded-3 px-4 btn-sm fw-bold"
                            style="background-color: #111;">Simpan Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT PROFIL ELEGANT & RESPONSIVE -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form action="<?= base_url('admin/update-profile') ?>" method="post" class="w-100">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                        <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2">
                            <i class="bi bi-person-gear text-warning"></i> Informasi Profil
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <p class="text-muted small mb-3">Perbarui nama lengkap atau username akun Anda di sini.</p>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control rounded-3 py-2" required
                                value="<?= session()->get('name') ?? '' ?>">
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold small text-secondary">Username</label>
                            <input type="text" name="username" class="form-control rounded-3 py-2" required
                                value="<?= session()->get('username') ?? '' ?>">
                        </div>
                    </div>
                    <div class="modal-footer bg-white px-4 py-3 border-0">
                        <button type="button" class="btn btn-light rounded-3 px-4 btn-sm fw-semibold"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark rounded-3 px-4 btn-sm fw-bold"
                            style="background-color: #111;">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-center p-4">
                <div class="modal-body p-0">
                    <div class="mb-3">
                        <div
                            style="width: 70px; height: 70px; background: rgba(212, 175, 55, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; border: 2px solid var(--accent-gold);">
                            <i class="bi bi-check-lg text-warning fs-1"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Berhasil!</h5>
                    <p class="text-muted small mb-4" id="successMessage">Perubahan berhasil disimpan ke dalam sistem.
                    </p>
                    <button type="button" class="btn btn-dark w-100 rounded-3 py-2 fw-bold" data-bs-dismiss="modal"
                        style="background-color: #111;">OK, Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script untuk Jam & Tanggal Real-Time -->
    <script>
    function updateClock() {
        const now = new Date();
        const options = {
            weekday: 'short',
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };
        document.getElementById('live-clock').innerText = now.toLocaleDateString('id-ID', options).replace(/\./g, ':');
    }
    setInterval(updateClock, 1000);
    updateClock();

    document.addEventListener("DOMContentLoaded", function() {
        <?php if (session()->getFlashdata('success_profile')): ?>
        document.getElementById('successMessage').innerText =
            "<?= session()->getFlashdata('success_profile') ?>";
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        <?php endif; ?>

        <?php if (session()->getFlashdata('success_password')): ?>
        document.getElementById('successMessage').innerText =
            "<?= session()->getFlashdata('success_password') ?>";
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        <?php endif; ?>
    });
    </script>
</body>

</html>