<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark">Form Lakukan Stok Opname</h1>
            <p class="text-secondary small mb-0">Sesuaikan stok fisik gudang berdasarkan nomor batch dan tanggal kedaluwarsa.</p>
        </div>
        <a href="<?= base_url('admin/products/stock-opname') ?>" class="btn btn-outline-secondary btn-sm rounded-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
        </a>
    </div>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/products/stock-opname/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 text-secondary">
                            <tr>
                                <th class="ps-4">Nama Produk</th>
                                <th>No. Batch</th>
                                <th>Expired Date</th>
                                <th class="text-center">Stok Sistem</th>
                                <th style="width: 180px;">Stok Fisik Real</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox display-6 d-block mb-2 text-secondary"></i>
                                        Belum ada produk atau batch aktif di sistem. Pastikan produk telah melalui menu Penerimaan Produk (*Restock*).
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $prod): ?>
                                    <?php if (!empty($prod['batches'])): ?>
                                        <?php foreach ($prod['batches'] as $batch): ?>
                                            <tr>
                                                <td class="ps-4 fw-semibold text-dark"><?= esc($prod['product_name']) ?></td>
                                                <td>
                                                    <span class="badge bg-light text-dark border px-2 py-1 font-monospace">
                                                        <?= esc($batch['batch_number']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-danger small fw-semibold">
                                                        <i class="bi bi-calendar-event me-1"></i> <?= esc($batch['expired_date']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center fw-bold text-secondary">
                                                    <?= esc($batch['qty_remaining']) ?>
                                                </td>
                                                <td>
                                                    <!-- Input untuk stok fisik riil, dikelompokkan berdasarkan ID Batch -->
                                                    <input type="number"
                                                        name="opname[<?= $batch['id'] ?>][physical_stock]"
                                                        class="form-control form-control-sm text-center fw-bold"
                                                        value="<?= esc($batch['qty_remaining']) ?>"
                                                        required min="0">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td class="ps-4 text-muted"><?= esc($prod['product_name']) ?></td>
                                            <td colspan="4" class="text-muted small fst-italic">Tidak ada batch aktif (Stok 0 / Belum ada penerimaan)</td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-dark px-4 py-2 rounded-3 fw-semibold shadow-sm">
                <i class="bi bi-save me-1"></i> Simpan & Sinkronkan Stok Opname
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>