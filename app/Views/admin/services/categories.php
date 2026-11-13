<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-3">

    <!-- Modal Alert Sukses (Auto-close 4 detik) -->
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

    <!-- Notifikasi Error (jika ada) tetap dipertahankan dengan alert biasa -->
    <?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-tags-fill text-warning me-2"></i> Kategori Layanan</h3>
            <p class="text-muted small mb-0">Kelompokkan jenis jasa layanan cukur untuk mempermudah manajemen.</p>
        </div>
        <button type="button" class="btn btn-dark rounded-3 px-3 text-warning fw-semibold shadow-sm"
            data-bs-toggle="modal" data-bs-target="#modalAddCategory">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </button>
    </div>

    <!-- Tabel Data Kategori -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small">
                    <tr>
                        <th class="py-3">No</th>
                        <th class="py-3">Nama Kategori</th>
                        <th class="py-3">Deskripsi</th>
                        <th class="py-3 text-center" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data kategori layanan.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($categories as $index => $cat): ?>
                    <tr>
                        <td class="fw-semibold text-secondary"><?= $index + 1 ?></td>
                        <td>
                            <span class="fw-bold text-dark"><?= esc($cat['category_name']) ?></span>
                        </td>
                        <td>
                            <span class="text-muted small"><?= esc($cat['description'] ?? '-') ?></span>
                        </td>
                        <td class="text-center">
                            <!-- Tombol Edit (Memicu Modal) -->
                            <button type="button"
                                class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-primary me-1"
                                data-bs-toggle="modal" data-bs-target="#modalEditCategory<?= $cat['id'] ?>"
                                title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- Tombol Hapus (Memicu Modal Konfirmasi) -->
                            <button type="button" class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-danger"
                                data-bs-toggle="modal" data-bs-target="#modalDeleteCategory<?= $cat['id'] ?>"
                                title="Hapus">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Edit Kategori untuk tiap item -->
                    <div class="modal fade" id="modalEditCategory<?= $cat['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 rounded-4 shadow">
                                <form action="<?= base_url('admin/services/categories/update/' . $cat['id']) ?>"
                                    method="POST">
                                    <?= csrf_field() ?>
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">
                                            <i class="bi bi-pencil-square text-primary me-2"></i> Edit Kategori Layanan
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body py-3">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Nama Kategori</label>
                                            <input type="text" name="category_name"
                                                value="<?= esc($cat['category_name']) ?>" class="form-control rounded-3"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">Deskripsi (Opsional)</label>
                                            <textarea name="description" class="form-control rounded-3"
                                                rows="3"><?= esc($cat['description']) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0 pb-3">
                                        <button type="button" class="btn btn-light border rounded-3 px-3"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-dark rounded-3 px-4 text-warning">Simpan
                                            Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Konfirmasi Hapus untuk tiap item -->
                    <div class="modal fade" id="modalDeleteCategory<?= $cat['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content border-0 rounded-4 shadow text-center p-3">
                                <div class="modal-body">
                                    <div class="text-danger mb-3">
                                        <i class="bi bi-exclamation-circle fs-1"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">Hapus Kategori?</h5>
                                    <p class="text-muted small mb-4">Apakah Anda yakin ingin menghapus kategori
                                        <strong><?= esc($cat['category_name']) ?></strong>?
                                    </p>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-light border rounded-3 px-3"
                                            data-bs-dismiss="modal">Batal</button>
                                        <a href="<?= base_url('admin/services/categories/delete/' . $cat['id']) ?>"
                                            class="btn btn-sm btn-danger rounded-3 px-3">Ya, Hapus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="modalAddCategory" tabindex="-1" aria-labelledby="modalAddCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="<?= base_url('admin/services/categories/create') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalAddCategoryLabel">
                        <i class="bi bi-plus-circle text-warning me-2"></i> Tambah Kategori Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Kategori</label>
                        <input type="text" name="category_name" class="form-control rounded-3"
                            placeholder="Contoh: Hair Care & Treatment" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control rounded-3" rows="3"
                            placeholder="Keterangan singkat kategori..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-3">
                    <button type="button" class="btn btn-light border rounded-3 px-3"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-3 px-4 text-warning">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>