<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Manajemen Waste Produk</h1>
            <p class="text-muted small mb-0">Catat dan pantau barang rusak, kedaluwarsa, atau susut yang memengaruhi pengurangan stok.</p>
        </div>
        <button type="button" class="btn btn-primary rounded-3 px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahWaste">
            <i class="bi bi-plus-lg me-1"></i> Catat Waste Baru
        </button>
    </div>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabel Data Waste -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="py-3 ps-4">Kode Waste</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Nama Produk</th>
                            <th class="py-3">No. Batch & Exp</th>
                            <th class="py-3 text-center">Jumlah Waste</th>
                            <th class="py-3">Alasan</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($wastes)): ?>
                            <?php foreach ($wastes as $row): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold font-monospace text-primary"><?= esc($row['waste_code']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['waste_date'])) ?></td>
                                    <td>
                                        <span class="fw-bold text-dark"><?= esc($row['product_name']) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace me-1"><?= esc($row['batch_number']) ?></span>
                                        <span class="text-muted small d-block">Exp: <?= esc($row['expired_date']) ?></span>
                                    </td>
                                    <td class="text-center fw-bold text-danger">-<?= esc($row['qty_waste']) ?></td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border px-2 py-1"><?= esc($row['reason']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <!-- Tombol Detail -->
                                        <button type="button" class="btn btn-sm btn-outline-info rounded-3 me-1 btn-detail-waste"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDetailWaste"
                                            data-code="<?= esc($row['waste_code']) ?>"
                                            data-date="<?= date('d/m/Y', strtotime($row['waste_date'])) ?>"
                                            data-product="<?= esc($row['product_name']) ?>"
                                            data-batch="<?= esc($row['batch_number']) ?>"
                                            data-exp="<?= esc($row['expired_date']) ?>"
                                            data-qty="<?= esc($row['qty_waste']) ?>"
                                            data-reason="<?= esc($row['reason']) ?>"
                                            data-notes="<?= esc($row['notes'] ?? 'Tidak ada catatan tambahan.') ?>">
                                            <i class="bi bi-info-circle"></i>
                                        </button>

                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-sm btn-outline-warning rounded-3 me-1 btn-edit-waste"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditWaste"
                                            data-id="<?= $row['id'] ?>"
                                            data-batchid="<?= $row['batch_id'] ?>"
                                            data-date="<?= $row['waste_date'] ?>"
                                            data-qty="<?= $row['qty_waste'] ?>"
                                            data-reason="<?= esc($row['reason']) ?>"
                                            data-notes="<?= esc($row['notes']) ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <a href="<?= base_url('admin/products/waste/delete/' . $row['id']) ?>" class="btn btn-sm btn-outline-danger rounded-3" onclick="return confirm('Yakin ingin menghapus data waste ini? Stok batch akan dikembalikan seperti semula.')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada catatan waste produk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Waste -->
<div class="modal fade" id="modalTambahWaste" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="<?= base_url('admin/products/waste/store') ?>" method="POST">
                <div class="modal-header bg-light px-4">
                    <h5 class="modal-title fw-bold text-dark" id="modalTambahLabel">Catat Waste / Barang Rusak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tanggal Pencatatan</label>
                        <input type="date" class="form-control rounded-3" name="waste_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Produk & Batch</label>
                        <select class="form-select rounded-3" name="batch_id" required>
                            <option value="" disabled selected>-- Pilih Produk (Batch & Sisa Stok) --</option>
                            <?php if (!empty($batches)): ?>
                                <?php foreach ($batches as $b): ?>
                                    <option value="<?= $b['batch_id'] ?>">
                                        <?= esc($b['product_name']) ?> | Batch: <?= esc($b['batch_number']) ?> (Sisa: <?= $b['qty_remaining'] ?>, Exp: <?= $b['expired_date'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jumlah Qty Waste</label>
                        <input type="number" class="form-control rounded-3" name="qty_waste" placeholder="Masukkan jumlah barang rusak/expired" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alasan Waste</label>
                        <select class="form-select rounded-3" name="reason" required>
                            <option value="Kadaluarsa (Expired)">Kadaluarsa (Expired)</option>
                            <option value="Rusak Kemasan">Rusak Kemasan</option>
                            <option value="Pecah / Tumpah">Pecah / Tumpah</option>
                            <option value="Cacat Produksi">Cacat Produksi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keterangan / Catatan (Opsional)</label>
                        <textarea class="form-control rounded-3" name="notes" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4">
                    <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan & Potong Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Waste -->
<div class="modal fade" id="modalEditWaste" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form id="formEditWaste" method="POST">
                <div class="modal-header bg-light px-4">
                    <h5 class="modal-title fw-bold text-dark" id="modalEditLabel">Edit Data Waste</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tanggal Pencatatan</label>
                        <input type="date" class="form-control rounded-3" id="edit_date" name="waste_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Produk & Batch</label>
                        <select class="form-select rounded-3" id="edit_batch_id" name="batch_id" required>
                            <?php if (!empty($batches)): ?>
                                <?php foreach ($batches as $b): ?>
                                    <option value="<?= $b['batch_id'] ?>">
                                        <?= esc($b['product_name']) ?> | Batch: <?= esc($b['batch_number']) ?> (Sisa: <?= $b['qty_remaining'] ?>, Exp: <?= $b['expired_date'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jumlah Qty Waste</label>
                        <input type="number" class="form-control rounded-3" id="edit_qty" name="qty_waste" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alasan Waste</label>
                        <select class="form-select rounded-3" id="edit_reason" name="reason" required>
                            <option value="Kadaluarsa (Expired)">Kadaluarsa (Expired)</option>
                            <option value="Rusak Kemasan">Rusak Kemasan</option>
                            <option value="Pecah / Tumpah">Pecah / Tumpah</option>
                            <option value="Cacat Produksi">Cacat Produksi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keterangan / Catatan (Opsional)</label>
                        <textarea class="form-control rounded-3" id="edit_notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4">
                    <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Waste -->
<div class="modal fade" id="modalDetailWaste" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalDetailLabel">Detail Catatan Waste</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted small">Kode Waste:</span>
                        <strong id="detail_code" class="font-monospace text-primary"></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted small">Tanggal:</span>
                        <strong id="detail_date" class="text-dark"></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted small">Nama Produk:</span>
                        <strong id="detail_product" class="text-dark"></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted small">Nomor Batch:</span>
                        <span id="detail_batch" class="badge bg-light text-dark border font-monospace"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted small">Tanggal Kedaluwarsa:</span>
                        <strong id="detail_exp" class="text-secondary"></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted small">Jumlah Qty Waste:</span>
                        <strong id="detail_qty" class="text-danger"></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted small">Alasan:</span>
                        <span id="detail_reason" class="badge bg-warning-subtle text-warning-emphasis border"></span>
                    </li>
                    <li class="list-group-item px-0 pt-3">
                        <span class="text-muted small d-block mb-1">Catatan Tambahan:</span>
                        <p id="detail_notes" class="text-dark bg-light p-3 rounded-3 mb-0 small"></p>
                    </li>
                </ul>
            </div>
            <div class="modal-footer bg-light px-4">
                <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Script untuk Handler Modal Detail & Edit -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handler Modal Detail
        const detailButtons = document.querySelectorAll('.btn-detail-waste');
        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('detail_code').innerText = this.getAttribute('data-code');
                document.getElementById('detail_date').innerText = this.getAttribute('data-date');
                document.getElementById('detail_product').innerText = this.getAttribute('data-product');
                document.getElementById('detail_batch').innerText = this.getAttribute('data-batch');
                document.getElementById('detail_exp').innerText = this.getAttribute('data-exp');
                document.getElementById('detail_qty').innerText = '-' + this.getAttribute('data-qty');
                document.getElementById('detail_reason').innerText = this.getAttribute('data-reason');
                document.getElementById('detail_notes').innerText = this.getAttribute('data-notes');
            });
        });

        // Handler Modal Edit
        const editButtons = document.querySelectorAll('.btn-edit-waste');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('formEditWaste').action = '<?= base_url('admin/products/waste/update/') ?>' + id;
                document.getElementById('edit_date').value = this.getAttribute('data-date');
                document.getElementById('edit_batch_id').value = this.getAttribute('data-batchid');
                document.getElementById('edit_qty').value = this.getAttribute('data-qty');
                document.getElementById('edit_reason').value = this.getAttribute('data-reason');
                document.getElementById('edit_notes').value = this.getAttribute('data-notes');
            });
        });
    });
</script>
<?= $this->endSection() ?>