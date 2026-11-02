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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            padding: 20px 15px;
            list-style: none;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
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
            z-index: 999;
        }

        .user-profile-dropdown .dropdown-toggle::after {
            display: none;
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

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="logo-icon">
                <i class="bi bi-scissors"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-white">Barber POS</h6>
                <small class="text-muted" style="font-size: 11px;">Admin Control Panel</small>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="<?= base_url('admin/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/services') ?>">
                    <i class="bi bi-grid-fill"></i> Kelola Layanan
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/products') ?>">
                    <i class="bi bi-box-seam"></i> Kelola Produk
                </a>
            </li>
            <!-- TAMBAHAN MENU POS / KASIR -->
            <li>
                <a href="<?= base_url('admin/pos') ?>">
                    <i class="bi bi-cash-coin"></i> Kasir (POS)
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-receipt"></i> Laporan Transaksi
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-people-fill"></i> Manajemen Pengguna
                </a>
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

            <!-- PROFILE DROPDOWN DI KANAN ATAS -->
            <div class="dropdown user-profile-dropdown">
                <a href="#" class="d-flex align-items-center gap-3 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-sm-block">
                        <span class="d-block fw-bold text-dark" style="font-size: 14px;"><?= session()->get('name') ?? 'Administrator' ?></span>
                        <span class="d-block text-muted" style="font-size: 11px;">Owner / Admin</span>
                        <span class="d-block text-secondary" style="font-size: 10px;" id="live-clock">Memuat waktu...</span>
                    </div>
                    <div class="user-avatar">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 py-2 mt-2" style="min-width: 200px;">
                    <li>
                        <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 fw-semibold text-secondary" href="#" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                            <i class="bi bi-key-fill text-warning"></i> Ganti Password
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-1">
                    </li>
                    <li>
                        <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 fw-semibold text-danger" href="<?= base_url('logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- CONTENT BODY -->
        <div class="content-body">
            <?= $this->renderSection('content') ?>
        </div>

    </div>

    <!-- MODAL GANTI PASSWORD ELEGANT -->
    <div class="modal fade" id="editPasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="<?= base_url('admin/update-password') ?>" method="post" class="w-100">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                        <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2">
                            <i class="bi bi-shield-lock-fill text-warning"></i> Keamanan Akun
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 bg-light">
                        <p class="text-muted small mb-3">Silakan masukkan password lama dan password baru Anda untuk memperbarui keamanan akun.</p>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Password Lama</label>
                            <input type="password" name="old_password" class="form-control rounded-3 py-2" required placeholder="••••••••">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Password Baru</label>
                            <input type="password" name="new_password" class="form-control rounded-3 py-2" required placeholder="••••••••">
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold small text-secondary">Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" class="form-control rounded-3 py-2" required placeholder="••••••••">
                        </div>
                    </div>
                    <div class="modal-footer bg-white px-4 py-3 border-0">
                        <button type="button" class="btn btn-light rounded-3 px-4 btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark rounded-3 px-4 btn-sm fw-bold" style="background-color: #111;">Simpan Password</button>
                    </div>
                </div>
            </form>
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
    </script>
</body>

</html>