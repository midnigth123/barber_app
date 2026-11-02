<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="row g-4 mb-4">
    <!-- Card Statistik: Total Layanan -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 position-relative overflow-hidden">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-uppercase text-secondary fw-bold small mb-1"
                        style="font-size: 11px; letter-spacing: 0.8px;">Total Layanan</p>
                    <h3 class="fw-bold text-dark mb-0"><?= $total_services ?? 0 ?></h3>
                </div>
                <div class="rounded-3 d-flex align-items-center justify-content-center text-dark"
                    style="width: 50px; height: 50px; background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%); font-size: 22px;">
                    <i class="bi bi-grid-fill"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-top border-light d-flex align-items-center text-success small fw-semibold">
                <i class="bi bi-arrow-up-right me-1"></i> Terdaftar di sistem POS
            </div>
        </div>
    </div>

    <!-- Card Statistik: Total Produk -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 position-relative overflow-hidden">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-uppercase text-secondary fw-bold small mb-1"
                        style="font-size: 11px; letter-spacing: 0.8px;">Total Produk / Barang</p>
                    <h3 class="fw-bold text-dark mb-0">0</h3>
                </div>
                <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                    style="width: 50px; height: 50px; background: #111111; font-size: 22px;">
                    <i class="bi bi-box-seam" style="color: #d4af37;"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-top border-light d-flex align-items-center text-secondary small fw-semibold">
                <i class="bi bi-dash me-1"></i> Siap dikelola
            </div>
        </div>
    </div>

    <!-- Card Statistik: Pendapatan Hari Ini -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 position-relative overflow-hidden">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-uppercase text-secondary fw-bold small mb-1"
                        style="font-size: 11px; letter-spacing: 0.8px;">Estimasi Pendapatan</p>
                    <h3 class="fw-bold text-success mb-0">Rp 0</h3>
                </div>
                <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10"
                    style="width: 50px; height: 50px; font-size: 22px;">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-top border-light d-flex align-items-center text-success small fw-semibold">
                <i class="bi bi-calendar-check me-1"></i> Hari ini
            </div>
        </div>
    </div>
</div>

<!-- Welcome Banner / Section -->
<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-white position-relative overflow-hidden"
            style="background: linear-gradient(135deg, #111111 0%, #2c2c2c 100%);">
            <div class="position-relative z-1 py-3">
                <h3 class="fw-bold mb-2">Selamat Datang di Panel Admin,
                    <?= session()->get('name') ?? 'Administrator' ?>! 👋</h3>
                <p class="text-white-55 mb-4" style="max-width: 600px;">
                    Kelola operasional barbershop Anda dengan mudah melalui sistem POS terintegrasi. Pantau daftar
                    layanan, produk, serta laporan transaksi harian secara real-time.
                </p>
                <a href="<?= base_url('admin/services') ?>" class="btn px-4 py-2 rounded-3 text-dark fw-bold shadow-sm"
                    style="background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%); font-size: 14px;">
                    <i class="bi bi-grid-fill me-1"></i> Kelola Layanan Sekarang
                </a>
            </div>
            <div class="position-absolute end-0 bottom-0 translate-middle-y opacity-10 pe-4 d-none d-md-block"
                style="font-size: 140px; color: #d4af37;">
                <i class="bi bi-scissors"></i>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>