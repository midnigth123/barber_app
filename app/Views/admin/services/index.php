<?= $this->extend('admin/layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-12">

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <!-- Card Header -->
            <div class="card-header bg-white py-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom border-light">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Daftar Layanan Barbershop</h4>
                    <p class="text-muted small mb-0">Kelola daftar harga dan jenis potongan rambut / perawatan secara terpusat</p>
                </div>
                <button class="btn px-4 py-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 text-dark" data-bs-toggle="modal" data-bs-target="#addServiceModal" style="background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%); font-weight: 700; font-size: 14px; border: none; transition: all 0.3s ease;">
                    <i class="bi bi-plus-lg"></i> Tambah Layanan
                </button>
            </div>

            <!-- Card Body / Table -->
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-uppercase text-secondary fs-7" style="font-size: 11px; letter-spacing: 0.8px; background-color: #f8f9fa;">
                            <tr>
                                <th class="py-3 px-3 rounded-start" width="5%">No</th>
                                <th class="py-3">Nama Layanan</th>
                                <th class="py-3">Harga</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-end rounded-end" width="22%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($services)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                        Belum ada data layanan tersedia.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1;
                                foreach ($services as $s): ?>
                                    <tr>
                                        <td class="px-3 fw-bold text-secondary"><?= $no++ ?></td>
                                        <td>
                                            <span class="fw-bold text-dark"><?= $s['service_name'] ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold" style="color: #2b7a4b;">Rp <?= number_format($s['price'], 0, ',', '.') ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $s['is_active'] == 1 ? 'success' : 'secondary' ?> bg-opacity-10 text-<?= $s['is_active'] == 1 ? 'success' : 'secondary' ?> px-3 py-2 rounded-pill fw-semibold" style="font-size: 11px;">
                                                <i class="bi bi-dot"></i> <?= $s['is_active'] == 1 ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <button class="btn btn-sm btn-light border shadow-sm rounded-3 px-2 py-1 text-info d-flex align-items-center gap-1 btn-detail"
                                                    data-bs-toggle="modal" data-bs-target="#detailServiceModal"
                                                    data-name="<?= $s['service_name'] ?>"
                                                    data-price="Rp <?= number_format($s['price'], 0, ',', '.') ?>"
                                                    data-status="<?= $s['is_active'] == 1 ? 'Aktif' : 'Nonaktif' ?>"
                                                    title="Detail Layanan" style="font-size: 12px; font-weight: 600; background: #fdfdfd;">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                <button class="btn btn-sm btn-light border shadow-sm rounded-3 px-2 py-1 text-primary d-flex align-items-center gap-1 btn-edit"
                                                    data-bs-toggle="modal" data-bs-target="#editServiceModal"
                                                    data-id="<?= $s['id'] ?? '' ?>"
                                                    data-name="<?= $s['service_name'] ?>"
                                                    data-price="<?= $s['price'] ?>"
                                                    title="Edit Layanan" style="font-size: 12px; font-weight: 600; background: #fdfdfd;">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <button class="btn btn-sm btn-light border shadow-sm rounded-3 px-2 py-1 text-danger d-flex align-items-center gap-1 btn-delete"
                                                    data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                    data-id="<?= $s['id'] ?? '' ?>"
                                                    title="Hapus Layanan" style="font-size: 12px; font-weight: 600; background: #fdfdfd;">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Bagian Paginasi -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top border-light gap-3">
                    <div class="text-muted small">
                        Menampilkan <span class="fw-semibold text-dark">data layanan</span> barbershop terkini
                    </div>

                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0 shadow-sm rounded-pill overflow-hidden border">
                            <li class="page-item disabled">
                                <a class="page-link border-0 bg-white text-secondary px-3 py-2" href="#" tabindex="-1">
                                    <i class="bi bi-chevron-left small"></i>
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link border-0 text-dark fw-bold px-3 py-2" href="#" style="background-color: #d4af37;">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link border-0 text-dark bg-white px-3 py-2 fw-semibold" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link border-0 text-dark bg-white px-3 py-2 fw-semibold" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link border-0 bg-white text-dark px-3 py-2" href="#">
                                    <i class="bi bi-chevron-right small"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- ================= MODAL ALERT / SUKSES OTOMATIS HILANG ================= -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="modal fade" id="successAlertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4" style="background: #ffffff;">
                <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 65px; height: 65px; background: rgba(212, 175, 55, 0.15); color: #b8860b; font-size: 32px;">
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

            // Otomatis menutup modal setelah 4 detik (4000 milidetik)
            setTimeout(function() {
                successModal.hide();
            }, 4000);
        });
    </script>
<?php endif; ?>

<!-- ================= 1. MODAL TAMBAH LAYANAN ================= -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('admin/services/create') ?>" method="post" class="w-100">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header text-white px-4 py-3 border-0" style="background: #111111;">
                    <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle-fill" style="color: #d4af37;"></i> Tambah Layanan Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Nama Layanan</label>
                        <input type="text" name="service_name" class="form-control rounded-3 py-2 bg-white" required placeholder="Contoh: Gentlemen Haircut & Wash">
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold small text-secondary">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control rounded-3 py-2 bg-white" required placeholder="Contoh: 50000">
                    </div>
                </div>
                <div class="modal-footer bg-white px-4 py-3 border-0">
                    <button type="button" class="btn btn-light rounded-3 px-4 btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn rounded-3 px-4 btn-sm fw-bold shadow-sm text-dark" style="background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%);">Simpan Layanan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= 2. MODAL EDIT LAYANAN ================= -->
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditService" action="" method="post" class="w-100">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header text-white px-4 py-3 border-0" style="background: #111111;">
                    <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-warning"></i> Edit Layanan Barbershop
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Nama Layanan</label>
                        <input type="text" name="service_name" id="edit_service_name" class="form-control rounded-3 py-2 bg-white" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold small text-secondary">Harga (Rp)</label>
                        <input type="number" name="price" id="edit_price" class="form-control rounded-3 py-2 bg-white" required>
                    </div>
                </div>
                <div class="modal-footer bg-white px-4 py-3 border-0">
                    <button type="button" class="btn btn-light rounded-3 px-4 btn-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn rounded-3 px-4 btn-sm fw-bold shadow-sm text-dark" style="background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%);">Perbarui Layanan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= 3. MODAL DETAIL LAYANAN ================= -->
<div class="modal fade" id="detailServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header text-white px-4 py-3 border-0" style="background: #111111;">
                <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill text-info"></i> Detail Informasi Layanan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="bg-white p-3 rounded-3 shadow-sm border border-light">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-0">
                            <span class="text-secondary small fw-semibold">Nama Layanan</span>
                            <span class="fw-bold text-dark" id="detail-name">-</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-0">
                            <span class="text-secondary small fw-semibold">Tarif Harga</span>
                            <span class="fw-bold text-success" id="detail-price">-</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-0">
                            <span class="text-secondary small fw-semibold">Status Sistem</span>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold" id="detail-status">-</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer bg-white px-4 py-3 border-0">
                <button type="button" class="btn btn-dark rounded-3 px-4 btn-sm fw-semibold" data-bs-dismiss="modal" style="background: #111;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ================= 4. MODAL KONFIRMASI HAPUS ================= -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-center p-3">
            <div class="modal-body pt-4">
                <div class="avatar-icon mb-3 mx-auto d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle" style="width: 60px; height: 60px; font-size: 28px;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Hapus Layanan?</h5>
                <p class="text-muted small mb-4">Tindakan ini tidak dapat dibatalkan. Data layanan akan dihapus permanen dari sistem.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light rounded-3 px-3 py-2 btn-sm fw-semibold w-50" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger rounded-3 px-3 py-2 btn-sm fw-bold w-50 shadow-sm">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= JAVASCRIPT MODAL LAINNYA ================= -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Script Edit
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const price = this.getAttribute('data-price');

                document.getElementById('edit_service_name').value = name;
                document.getElementById('edit_price').value = price;
                document.getElementById('formEditService').action = "<?= base_url('admin/services/update/') ?>" + id;
            });
        });

        // Script Detail
        const detailButtons = document.querySelectorAll('.btn-detail');
        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('detail-name').innerText = this.getAttribute('data-name');
                document.getElementById('detail-price').innerText = this.getAttribute('data-price');
                document.getElementById('detail-status').innerText = this.getAttribute('data-status');
            });
        });

        // Script Hapus
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('confirmDeleteBtn').href = "<?= base_url('admin/services/delete/') ?>" + id;
            });
        });
    });
</script>

<?= $this->endSection() ?>