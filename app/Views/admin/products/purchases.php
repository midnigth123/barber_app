<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-3">

    <!-- MODAL ALERT SUKSES (Auto-close 4 detik) -->
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

    <!-- Notifikasi Error (jika ada) -->
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-box-arrow-in-down text-warning me-2"></i> Penerimaan Produk
                (Restock & FEFO)</h3>
            <p class="text-muted small mb-0">Catat barang masuk dari supplier dengan sistem nomor batch otomatis dan
                tanggal kedaluwarsa.</p>
        </div>
        <button type="button" class="btn btn-dark rounded-3 px-3 text-warning fw-semibold shadow-sm"
            data-bs-toggle="modal" data-bs-target="#modalAddPurchase">
            <i class="bi bi-plus-lg me-1"></i> Catat Penerimaan Baru
        </button>
    </div>

    <!-- Tabel Riwayat Penerimaan -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small">
                    <tr>
                        <th class="py-3">No</th>
                        <th class="py-3">No. Faktur / Nota</th>
                        <th class="py-3">No. Batch</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Supplier</th>
                        <th class="py-3">Nama Produk</th>
                        <th class="py-3 text-center">Jumlah Masuk</th>
                        <th class="py-3">Expired Date</th>
                        <th class="py-3">Harga Beli</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($purchases)): ?>
                    <tr>
                        <td colspan="11" class="text-center py-4 text-muted">Belum ada riwayat penerimaan produk.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($purchases as $index => $item): ?>
                    <tr>
                        <td class="fw-semibold text-secondary"><?= $index + 1 ?></td>
                        <td><span
                                class="badge bg-light text-dark border px-2 py-1"><?= esc($item['invoice_number']) ?></span>
                        </td>
                        <td><span
                                class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 font-monospace"><?= esc($item['batch_number'] ?? '-') ?></span>
                        </td>
                        <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></td>
                        <td class="fw-semibold"><?= esc($item['supplier_name'] ?? '-') ?></td>
                        <td><span class="fw-bold text-dark"><?= esc($item['product_name']) ?></span></td>
                        <td class="text-center">
                            <span
                                class="badge bg-success bg-opacity-10 text-success px-2 py-1">+<?= esc($item['qty_received']) ?>
                                Unit</span>
                        </td>
                        <td>
                            <span class="text-danger small fw-semibold">
                                <i class="bi bi-calendar-event me-1"></i>
                                <?= $item['expired_date'] ? date('d/m/Y', strtotime($item['expired_date'])) : '-' ?>
                            </span>
                        </td>
                        <td class="text-muted">Rp <?= number_format($item['purchase_price'], 0, ',', '.') ?></td>
                        <td class="text-center">
                            <?php if (($item['status'] ?? 'pending') === 'confirmed'): ?>
                            <span class="badge bg-success px-2 py-1">Selesai / Dikonfirmasi</span>
                            <?php else: ?>
                            <span class="badge bg-warning text-dark px-2 py-1">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if (($item['status'] ?? 'pending') !== 'confirmed'): ?>
                            <button type="button" class="btn btn-sm btn-success rounded-3 px-3 py-1 shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#modalConfirmReceive"
                                data-url="<?= base_url('admin/products/receiving/confirm/' . $item['id']) ?>">
                                <i class="bi bi-check-circle me-1"></i> Konfirmasi
                            </button>
                            <?php else: ?>
                            <span class="text-muted small"><i class="bi bi-check-all text-success"></i> Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Penerimaan Produk -->
<div class="modal fade" id="modalAddPurchase" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="<?= base_url('admin/products/receiving/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-box-arrow-in-down text-warning me-2"></i> Input Penerimaan Produk (FEFO)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">No. Faktur / Nota dari Supplier</label>
                        <input type="text" name="invoice_number" class="form-control rounded-3"
                            placeholder="Contoh: INV/2026/11/001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Supplier / Distributor</label>
                        <input type="text" name="supplier_name" class="form-control rounded-3"
                            placeholder="Contoh: PT Sumber Rambut Makmur">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Pilih Produk</label>
                        <select name="product_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php if (!empty($products) && is_array($products)): ?>
                            <?php foreach ($products as $prod): ?>
                            <option value="<?= $prod['id'] ?>"><?= esc($prod['product_name']) ?> (Stok saat ini:
                                <?= $prod['stock'] ?>)</option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">Jumlah Masuk (Qty)</label>
                            <input type="number" name="qty_received" class="form-control rounded-3" min="1"
                                placeholder="10" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">Tanggal Kedaluwarsa (Expired)</label>
                            <input type="date" name="expired_date" class="form-control rounded-3" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Harga Beli Satuan (Rp)</label>
                        <input type="number" name="purchase_price" class="form-control rounded-3" placeholder="45000"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Catatan / Keterangan (Opsional)</label>
                        <textarea name="notes" class="form-control rounded-3" rows="2"
                            placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-3">
                    <button type="button" class="btn btn-light border rounded-3 px-3"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-3 px-4 text-warning">Simpan Penerimaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Terima / Restock -->
<div class="modal fade" id="modalConfirmReceive" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-question-circle-fill text-warning display-4"></i>
                </div>
                <h5 class="fw-bold mb-2">Konfirmasi Penerimaan</h5>
                <p class="text-muted small mb-4">Stok dan data batch FEFO akan otomatis masuk ke sistem. Lanjutkan?</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light border rounded-3 px-3 w-50"
                        data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="btnConfirmAction" class="btn btn-success rounded-3 px-3 w-50">Ya, Lanjutkan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk modal konfirmasi & auto-dismiss alert error -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tangkap event saat modal konfirmasi akan ditampilkan
    const modalConfirmReceive = document.getElementById('modalConfirmReceive');
    if (modalConfirmReceive) {
        modalConfirmReceive.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget;
            let url = button.getAttribute('data-url');
            let btnConfirm = document.getElementById('btnConfirmAction');
            btnConfirm.href = url;
        });
    }

    // Auto-dismiss alert error dalam 4 detik
    let alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 4000);
    });
});
</script>

<?= $this->endSection() ?>