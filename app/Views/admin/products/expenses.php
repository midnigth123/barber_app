<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Manajemen Pengeluaran</h1>
            <p class="text-muted small mb-0">Kelola dan pantau seluruh catatan pengeluaran operasional toko/apotek.</p>
        </div>
        <button type="button" class="btn btn-primary rounded-3 px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahExpense">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pengeluaran
        </button>
    </div>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Card Total Pengeluaran -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning p-3 rounded-4 me-3">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Total Keseluruhan Pengeluaran</span>
                        <h4 class="fw-bold text-dark mb-0">Rp <?= number_format($totalExpense ?? 0, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Pengeluaran -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="py-3 ps-4">Tanggal</th>
                            <th class="py-3">Judul / Keperluan</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Jumlah (Nominal)</th>
                            <th class="py-3">Keterangan</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($expenses)): ?>
                            <?php foreach ($expenses as $row): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-secondary"><?= date('d/m/Y', strtotime($row['expense_date'])) ?></td>
                                    <td>
                                        <span class="fw-bold text-dark"><?= esc($row['title']) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1"><?= esc($row['category']) ?></span>
                                    </td>
                                    <td class="fw-bold text-danger">Rp <?= number_format($row['amount'], 0, ',', '.') ?></td>
                                    <td class="text-muted small">
                                        <?= !empty($row['description']) ? esc($row['description']) : '<span class="text-italic text-muted">-</span>' ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- Tombol Detail -->
                                        <button type="button" class="btn btn-sm btn-outline-info rounded-3 me-1 btn-detail-expense"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDetailExpense"
                                            data-date="<?= date('d/m/Y', strtotime($row['expense_date'])) ?>"
                                            data-title="<?= esc($row['title']) ?>"
                                            data-category="<?= esc($row['category']) ?>"
                                            data-amount="Rp <?= number_format($row['amount'], 0, ',', '.') ?>"
                                            data-description="<?= esc($row['description'] ?? 'Tidak ada keterangan tambahan.') ?>">
                                            <i class="bi bi-info-circle"></i>
                                        </button>

                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-sm btn-outline-warning rounded-3 me-1 btn-edit-expense"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditExpense"
                                            data-id="<?= $row['id'] ?>"
                                            data-date="<?= $row['expense_date'] ?>"
                                            data-title="<?= esc($row['title']) ?>"
                                            data-category="<?= esc($row['category']) ?>"
                                            data-amount="<?= $row['amount'] ?>"
                                            data-description="<?= esc($row['description']) ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <a href="<?= base_url('admin/products/expenses/delete/' . $row['id']) ?>" class="btn btn-sm btn-outline-danger rounded-3" onclick="return confirm('Yakin ingin menghapus data pengeluaran ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data pengeluaran yang tercatat.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengeluaran -->
<div class="modal fade" id="modalTambahExpense" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="<?= base_url('admin/products/expenses/store') ?>" method="POST">
                <div class="modal-header bg-light px-4">
                    <h5 class="modal-title fw-bold text-dark" id="modalTambahLabel">Tambah Pengeluaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tanggal Pengeluaran</label>
                        <input type="date" class="form-control rounded-3" name="expense_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Judul / Keperluan</label>
                        <input type="text" class="form-control rounded-3" name="title" placeholder="Contoh: Beli ATK / Bayar Listrik" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kategori</label>
                        <select class="form-select rounded-3" name="category">
                            <option value="Operasional">Operasional</option>
                            <option value="Belanja Barang">Belanja Barang</option>
                            <option value="Utilitas (Listrik/Air/Internet)">Utilitas (Listrik/Air/Internet)</option>
                            <option value="Gaji Karyawan">Gaji Karyawan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jumlah (Rp)</label>
                        <input type="number" class="form-control rounded-3" name="amount" placeholder="Contoh: 50000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keterangan Tambahan (Opsional)</label>
                        <textarea class="form-control rounded-3" name="description" rows="2" placeholder="Catatan detail..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4">
                    <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pengeluaran -->
<div class="modal fade" id="modalEditExpense" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form id="formEditExpense" method="POST">
                <div class="modal-header bg-light px-4">
                    <h5 class="modal-title fw-bold text-dark" id="modalEditLabel">Edit Pengeluaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tanggal Pengeluaran</label>
                        <input type="date" class="form-control rounded-3" id="edit_date" name="expense_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Judul / Keperluan</label>
                        <input type="text" class="form-control rounded-3" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kategori</label>
                        <select class="form-select rounded-3" id="edit_category" name="category">
                            <option value="Operasional">Operasional</option>
                            <option value="Belanja Barang">Belanja Barang</option>
                            <option value="Utilitas (Listrik/Air/Internet)">Utilitas (Listrik/Air/Internet)</option>
                            <option value="Gaji Karyawan">Gaji Karyawan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jumlah (Rp)</label>
                        <input type="number" class="form-control rounded-3" id="edit_amount" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keterangan Tambahan (Opsional)</label>
                        <textarea class="form-control rounded-3" id="edit_description" name="description" rows="2"></textarea>
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

<!-- Modal Detail Pengeluaran -->
<div class="modal fade" id="modalDetailExpense" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalDetailLabel">Detail Pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted small">Tanggal:</span>
                        <strong id="detail_date" class="text-dark"></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted small">Judul / Keperluan:</span>
                        <strong id="detail_title" class="text-dark"></strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted small">Kategori:</span>
                        <span id="detail_category" class="badge bg-light text-dark border"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted small">Nominal:</span>
                        <strong id="detail_amount" class="text-danger"></strong>
                    </li>
                    <li class="list-group-item px-0 pt-3">
                        <span class="text-muted small d-block mb-1">Keterangan Detail:</span>
                        <p id="detail_description" class="text-dark bg-light p-3 rounded-3 mb-0 small"></p>
                    </li>
                </ul>
            </div>
            <div class="modal-footer bg-light px-4">
                <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Mengisi Data ke Modal Edit & Detail -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Script untuk Modal Edit
        const editButtons = document.querySelectorAll('.btn-edit-expense');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const date = this.getAttribute('data-date');
                const title = this.getAttribute('data-title');
                const category = this.getAttribute('data-category');
                const amount = this.getAttribute('data-amount');
                const description = this.getAttribute('data-description');

                document.getElementById('formEditExpense').action = '<?= base_url('admin/products/expenses/update/') ?>' + id;
                document.getElementById('edit_date').value = date;
                document.getElementById('edit_title').value = title;
                document.getElementById('edit_category').value = category;
                document.getElementById('edit_amount').value = amount;
                document.getElementById('edit_description').value = description;
            });
        });

        // Script untuk Modal Detail
        const detailButtons = document.querySelectorAll('.btn-detail-expense');
        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('detail_date').innerText = this.getAttribute('data-date');
                document.getElementById('detail_title').innerText = this.getAttribute('data-title');
                document.getElementById('detail_category').innerText = this.getAttribute('data-category');
                document.getElementById('detail_amount').innerText = this.getAttribute('data-amount');
                document.getElementById('detail_description').innerText = this.getAttribute('data-description');
            });
        });
    });
</script>
<?= $this->endSection() ?>