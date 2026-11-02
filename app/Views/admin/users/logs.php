<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-3">

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-clock-history text-warning me-2"></i> Log Aktivitas Sistem</h3>
            <p class="text-muted small mb-0">Riwayat aktivitas dan jejak operasional pengguna pada aplikasi Barber POS.
            </p>
        </div>
    </div>

    <!-- Tabel Data Logs -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small">
                    <tr>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Aktivitas / Aksi</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada catatan log aktivitas.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td class="small text-muted text-nowrap">
                            <i class="bi bi-calendar3 me-1"></i> <?= $log['created_at'] ?? '-' ?>
                        </td>
                        <td>
                            <span class="fw-bold text-dark"><?= $log['user_name'] ?? 'System / Guest' ?></span>
                        </td>
                        <td>
                            <span class="badge bg-dark text-uppercase text-warning" style="font-size: 10px;">
                                <?= $log['role'] ?? 'SYSTEM' ?>
                            </span>
                        </td>
                        <td>
                            <span class="text-secondary small"><?= $log['activity'] ?></span>
                        </td>
                        <td class="small text-muted font-monospace">
                            <?= $log['ip_address'] ?? '-' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>