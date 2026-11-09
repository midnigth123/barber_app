<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark">Manajemen Stok Opname</h1>
            <p class="text-secondary small mb-0">Daftar riwayat dan penyesuaian stok fisik produk berbasis FEFO & Batch.</p>
        </div>
        <!-- Tombol untuk mulai melakukan opname baru -->
        <a href="<?= base_url('admin/products/stock-opname/create') ?>" class="btn btn-dark rounded-3 shadow-sm px-4 py-2 fw-semibold">
            <i class="bi bi-play-circle me-1"></i> Mulai Stok Opname
        </a>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle me-2"></i> <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="ps-4">Nama Produk</th>
                            <th>No. Batch</th>
                            <th>Expired Date</th>
                            <th class="text-center">Stok Tercatat (Sistem)</th>
                            <th class="text-end pe-4">Terakhir Opname / Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($history)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder2-open display-6 d-block mb-2 text-secondary"></i>
                                    Belum ada riwayat stok opname. Klik tombol <strong>"Mulai Stok Opname"</strong> di atas untuk melakukan pengecekan fisik.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($history as $row): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark"><?= esc($row['product_name']) ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 font-monospace">
                                            <?= esc($row['batch_number']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-danger small fw-semibold">
                                            <i class="bi bi-calendar-event me-1"></i> <?= esc($row['expired_date']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-primary">
                                        <?= esc($row['qty_remaining']) ?> Unit
                                    </td>
                                    <td class="text-end pe-4 text-secondary small">
                                        <?= esc($row['updated_at'] ?? '-') ?>
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
<?= $this->endSection() ?>