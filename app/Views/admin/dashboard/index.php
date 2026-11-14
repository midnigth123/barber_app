<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-4">

    <!-- MODAL ALERT SUKSES (Auto-close 4 detik jika ada flashdata) -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="modal fade" id="successAlertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4" style="background: #ffffff;">
                <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                    style="width: 65px; height: 65px; background: rgba(212, 175, 55, 0.15); color: #b8860b; font-size: 32px;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Berhasil!</h5>
                <p class="text-muted small mb-0"><?= session()->getFlashdata('success') ?></p>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var successModalEl = document.getElementById('successAlertModal');
        var successModal = new bootstrap.Modal(successModalEl);
        successModal.show();
        setTimeout(function() {
            successModal.hide();
        }, 4000);
    });
    </script>
    <?php endif; ?>

    <!-- Welcome Banner / Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-white position-relative overflow-hidden"
                style="background: linear-gradient(135deg, #111111 0%, #2c2c2c 100%);">
                <div class="position-relative z-1 py-2">
                    <h3 class="fw-bold mb-2">Selamat Datang di Panel Admin,
                        <?= session()->get('name') ?? 'Administrator' ?>! 👋</h3>
                    <p class="text-white-50 mb-4 small" style="max-width: 650px;">
                        Kelola operasional Barber POS secara menyeluruh. Pantau transaksi kasir, stok berbasis FEFO &
                        Batch, hingga deteksi dini produk kedaluwarsa secara real-time.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= base_url('admin/pos') ?>"
                            class="btn px-4 py-2 rounded-3 text-dark fw-bold shadow-sm"
                            style="background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%); font-size: 13px;">
                            <i class="bi bi-cash-register me-1"></i> Buka Kasir POS
                        </a>
                        <a href="<?= base_url('admin/products/receiving') ?>"
                            class="btn btn-outline-light px-3 py-2 rounded-3 fw-semibold small">
                            <i class="bi bi-box-arrow-in-down me-1"></i> Restock Produk
                        </a>
                    </div>
                </div>
                <div class="position-absolute end-0 bottom-0 translate-middle-y opacity-10 pe-4 d-none d-md-block"
                    style="font-size: 130px; color: #d4af37;">
                    <i class="bi bi-scissors"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 1: Kartu Statistik Utama -->
    <div class="row g-4 mb-4">
        <!-- Card Statistik: Pendapatan Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 position-relative overflow-hidden h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-uppercase text-secondary fw-bold small mb-1"
                            style="font-size: 11px; letter-spacing: 0.8px;">Pendapatan Hari Ini</p>
                        <h4 class="fw-bold text-success mb-0">Rp <?= number_format($today_revenue ?? 0, 0, ',', '.') ?>
                        </h4>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-success bg-success bg-opacity-10"
                        style="width: 45px; height: 45px; font-size: 20px;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light d-flex align-items-center text-muted small">
                    <i class="bi bi-calendar-check me-1 text-success"></i> Real-time transaksi kasir
                </div>
            </div>
        </div>

        <!-- Card Statistik: Total Layanan -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 position-relative overflow-hidden h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-uppercase text-secondary fw-bold small mb-1"
                            style="font-size: 11px; letter-spacing: 0.8px;">Total Layanan</p>
                        <h4 class="fw-bold text-dark mb-0"><?= $total_services ?? 0 ?> <span
                                class="fs-6 fw-normal text-muted">Jasa</span></h4>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-dark"
                        style="width: 45px; height: 45px; background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%); font-size: 20px;">
                        <i class="bi bi-grid-fill"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light d-flex align-items-center text-muted small">
                    <a href="<?= base_url('admin/services') ?>"
                        class="text-decoration-none text-dark fw-semibold">Kelola Layanan <i
                            class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Card Statistik: Total Produk -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 position-relative overflow-hidden h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-uppercase text-secondary fw-bold small mb-1"
                            style="font-size: 11px; letter-spacing: 0.8px;">Total Produk Aktif</p>
                        <h4 class="fw-bold text-dark mb-0"><?= $total_products ?? 0 ?> <span
                                class="fs-6 fw-normal text-muted">Item</span></h4>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                        style="width: 45px; height: 45px; background: #111111; font-size: 20px;">
                        <i class="bi bi-box-seam" style="color: #d4af37;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light d-flex align-items-center text-muted small">
                    <a href="<?= base_url('admin/products/receiving') ?>"
                        class="text-decoration-none text-dark fw-semibold">Stok & FEFO <i
                            class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Card Statistik: Total Pengeluaran Bulan Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 position-relative overflow-hidden h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-uppercase text-secondary fw-bold small mb-1"
                            style="font-size: 11px; letter-spacing: 0.8px;">Pengeluaran Bulan Ini</p>
                        <h4 class="fw-bold text-danger mb-0">Rp <?= number_format($total_expense ?? 0, 0, ',', '.') ?>
                        </h4>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-danger bg-danger bg-opacity-10"
                        style="width: 45px; height: 45px; font-size: 20px;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-light d-flex align-items-center text-muted small">
                    <a href="<?= base_url('admin/products/expenses') ?>"
                        class="text-decoration-none text-danger fw-semibold">Manajemen Expense <i
                            class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 2: Grafik Penjualan (Kiri) & Peringatan Stok/Expired (Kanan) -->
    <div class="row g-4 mb-4">
        <!-- Grafik Penjualan 7 Hari Terakhir -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up-arrow text-warning me-2"></i> Grafik
                        Pendapatan (7 Hari Terakhir)</h5>
                    <span class="badge bg-light text-secondary border">Real-time</span>
                </div>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Widget Peringatan: Stok Menipis & Nyaris Expired -->
        <div class="col-lg-4">
            <div
                class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                        Peringatan Inventori</h5>

                    <!-- Peringatan Stok Menipis -->
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-2">Stok Hampir Habis (&le; 5
                            pcs)</span>
                        <div class="list-group list-group-flush border rounded-3 overflow-hidden"
                            style="max-height: 130px; overflow-y: auto;">
                            <?php if (empty($low_stock_products)): ?>
                            <div class="list-group-item text-muted small py-2 text-center fst-italic">Semua stok produk
                                aman.</div>
                            <?php else: ?>
                            <?php foreach ($low_stock_products as$low): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                <span class="small fw-semibold text-dark text-truncate"
                                    style="max-width: 150px;"><?= esc($low['product_name']) ?></span>
                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1"><?= $low['stock'] ?>
                                    Unit</span>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Peringatan Mendekati Kedaluwarsa -->
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-2">Mendekati Kedaluwarsa
                            (FEFO)</span>
                        <div class="list-group list-group-flush border rounded-3 overflow-hidden"
                            style="max-height: 130px; overflow-y: auto;">
                            <?php if (empty($expiring_batches)): ?>
                            <div class="list-group-item text-muted small py-2 text-center fst-italic">Tidak ada batch
                                mendekati expired.</div>
                            <?php else: ?>
                            <?php foreach ($expiring_batches as$exp): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                <div>
                                    <span class="small fw-semibold text-dark d-block text-truncate"
                                        style="max-width: 140px;"><?= esc($exp['product_name']) ?></span>
                                    <small class="text-muted font-monospace" style="font-size: 10px;">Batch:
                                        <?= esc($exp['batch_number']) ?></small>
                                </div>
                                <span
                                    class="badge bg-warning bg-opacity-15 text-dark border px-2 py-1 small"><?= date('d/m/Y', strtotime($exp['expired_date'])) ?></span>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-top text-end">
                    <a href="<?= base_url('admin/products/stock-opname') ?>"
                        class="btn btn-sm btn-dark rounded-3 px-3 text-warning">Periksa Gudang <i
                            class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 3: Tabel Transaksi Kasir Terbaru -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-warning me-2"></i> Transaksi
                        Kasir Terbaru</h5>
                    <a href="<?= base_url('admin/reports') ?>" class="btn btn-sm btn-outline-secondary rounded-3">Lihat
                        Semua Laporan</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small">
                            <tr>
                                <th class="py-3">No. Faktur</th>
                                <th class="py-3">Waktu</th>
                                <th class="py-3">Kasir / Pelanggan</th>
                                <th class="py-3">Total Belanja</th>
                                <th class="py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_transactions)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada transaksi tercatat hari
                                    ini.</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($recent_transactions as $trx): ?>
                            <tr>
                                <!-- Perbaikan dari invoice_number ke invoice_code -->
                                <td class="fw-semibold text-primary font-monospace"><?= esc($trx['invoice_code']) ?>
                                </td>
                                <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($trx['created_at'])) ?>
                                </td>
                                <td>
                                    <!-- Perbaikan ke customer_name sesuai model Anda -->
                                    <span class="fw-bold text-dark"><?= esc($trx['customer_name'] ?? 'Umum') ?></span>
                                </td>
                                <!-- Perbaikan dari grand_total ke total_amount -->
                                <td class="fw-bold text-success">Rp
                                    <?= number_format($trx['total_amount'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-2 py-1"><?= esc($trx['status'] ?? 'Berhasil') ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Sertakan Chart.js dari CDN untuk Grafik Penjualan -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('salesChart').getContext('2d');

    // Data dari Controller PHP
    const chartLabels = <?= json_encode($chart_labels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) ?>;
    const chartData = <?= json_encode($chart_data ?? [0, 0, 0, 0, 0, 0, 0]) ?>;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: chartData,
                borderColor: '#d4af37',
                backgroundColor: 'rgba(212, 175, 55, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#111111',
                pointBorderColor: '#d4af37',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.04)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        },
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>