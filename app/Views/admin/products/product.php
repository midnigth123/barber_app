<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-12">

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <!-- Card Header -->
            <div
                class="card-header bg-white py-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom border-light">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Kelola Produk & Barang</h4>
                    <p class="text-muted small mb-0">Kelola daftar inventaris produk, harga jual, dan stok barang</p>
                </div>
                <button
                    class="btn px-4 py-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 text-dark"
                    data-bs-toggle="modal" data-bs-target="#addProductModal"
                    style="background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%); font-weight: 700; font-size: 14px; border: none; transition: all 0.3s ease;">
                    <i class="bi bi-plus-lg"></i> Tambah Produk
                </button>
            </div>

            <!-- Card Body / Table -->
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-uppercase text-secondary fs-7"
                            style="font-size: 11px; letter-spacing: 0.8px; background-color: #f8f9fa;">
                            <tr>
                                <th class="py-3 px-3 rounded-start" width="5%">No</th>
                                <th class="py-3">Nama Produk</th>
                                <th class="py-3">Harga</th>
                                <th class="py-3">Stok</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-end rounded-end" width="22%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-box-seam fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                    Belum ada data produk tersedia.
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php $no = 1;
                                foreach ($products as $p): ?>
                            <tr>
                                <td class="px-3 fw-bold text-secondary"><?= $no++ ?></td>
                                <td>
                                    <!-- Nama produk dapat diklik untuk membuka modal detail batch FEFO -->
                                    <a href="javascript:void(0);"
                                        class="fw-bold text-dark text-decoration-none d-inline-flex align-items-center"
                                        data-id="<?= $p['id'] ?>" data-name="<?= esc($p['product_name']) ?>"
                                        data-bs-toggle="modal" data-bs-target="#modalBatchDetail"
                                        title="Klik untuk melihat detail batch FEFO">
                                        <i class="bi bi-box-seam text-warning me-2"></i>
                                        <span><?= esc($p['product_name']) ?></span>
                                        <i class="bi bi-info-circle text-muted ms-2 small"
                                            style="font-size: 0.85rem;"></i>
                                    </a>
                                </td>
                                <td>
                                    <span class="fw-bold" style="color: #2b7a4b;">Rp
                                        <?= number_format($p['selling_price'], 0, ',', '.') ?></span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark"><?= $p['stock'] ?> Pcs</span>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold"
                                        style="font-size: 11px;">
                                        <i class="bi bi-dot"></i> Tersedia
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <!-- Tombol Detail -->
                                        <button
                                            class="btn btn-sm btn-light border shadow-sm rounded-3 px-2 py-1 text-info d-flex align-items-center gap-1 btn-detail"
                                            data-bs-toggle="modal" data-bs-target="#detailProductModal"
                                            data-name="<?= $p['product_name'] ?>"
                                            data-price="Rp <?= number_format($p['selling_price'], 0, ',', '.') ?>"
                                            data-stock="<?= $p['stock'] ?> Pcs" title="Detail Produk"
                                            style="font-size: 12px; font-weight: 600; background: #fdfdfd;">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <!-- Tombol Edit -->
                                        <button
                                            class="btn btn-sm btn-light border shadow-sm rounded-3 px-2 py-1 text-primary d-flex align-items-center gap-1 btn-edit"
                                            data-bs-toggle="modal" data-bs-target="#editProductModal"
                                            data-id="<?= $p['id'] ?>" data-name="<?= $p['product_name'] ?>"
                                            data-price="<?= $p['selling_price'] ?>" data-stock="<?= $p['stock'] ?>"
                                            title="Edit Produk"
                                            style="font-size: 12px; font-weight: 600; background: #fdfdfd;">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button
                                            class="btn btn-sm btn-light border shadow-sm rounded-3 px-2 py-1 text-danger d-flex align-items-center gap-1 btn-delete"
                                            data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                            data-id="<?= $p['id'] ?>" title="Hapus Produk"
                                            style="font-size: 12px; font-weight: 600; background: #fdfdfd;">
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
            </div>
        </div>

    </div>
</div>

<!-- ================= MODAL ALERT SUKSES OTOMATIS ================= -->
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

<!-- ================= 1. MODAL TAMBAH PRODUK ================= -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('admin/products/create') ?>" method="post" class="w-100">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header text-white px-4 py-3 border-0" style="background: #111111;">
                    <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle-fill" style="color: #d4af37;"></i> Tambah Produk Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Nama Produk / Barang</label>
                        <input type="text" name="product_name" class="form-control rounded-3 py-2 bg-white" required
                            placeholder="Contoh: Pomade Suavecito Premium">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Harga Jual (Rp)</label>
                        <input type="number" name="price" class="form-control rounded-3 py-2 bg-white" required
                            placeholder="Contoh: 95000">
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold small text-secondary">Jumlah Stok</label>
                        <input type="number" name="stock" class="form-control rounded-3 py-2 bg-white" required
                            placeholder="Contoh: 25">
                    </div>
                </div>
                <div class="modal-footer bg-white px-4 py-3 border-0">
                    <button type="button" class="btn btn-light rounded-3 px-4 btn-sm fw-semibold"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn rounded-3 px-4 btn-sm fw-bold shadow-sm text-dark"
                        style="background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%);">Simpan Produk</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= 2. MODAL EDIT PRODUK ================= -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditProduct" action="" method="post" class="w-100">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header text-white px-4 py-3 border-0" style="background: #111111;">
                    <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-warning"></i> Edit Data Produk
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Nama Produk</label>
                        <input type="text" name="product_name" id="edit_product_name"
                            class="form-control rounded-3 py-2 bg-white" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Harga Jual (Rp)</label>
                        <input type="number" name="price" id="edit_price" class="form-control rounded-3 py-2 bg-white"
                            required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold small text-secondary">Jumlah Stok</label>
                        <input type="number" name="stock" id="edit_stock" class="form-control rounded-3 py-2 bg-white"
                            required>
                    </div>
                </div>
                <div class="modal-footer bg-white px-4 py-3 border-0">
                    <button type="button" class="btn btn-light rounded-3 px-4 btn-sm fw-semibold"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn rounded-3 px-4 btn-sm fw-bold shadow-sm text-dark"
                        style="background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%);">Perbarui Produk</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= 3. MODAL DETAIL PRODUK ================= -->
<div class="modal fade" id="detailProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header text-white px-4 py-3 border-0" style="background: #111111;">
                <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill text-info"></i> Detail Informasi Produk
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="bg-white p-3 rounded-3 shadow-sm border border-light">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-0">
                            <span class="text-secondary small fw-semibold">Nama Produk</span>
                            <span class="fw-bold text-dark" id="detail-name">-</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-0">
                            <span class="text-secondary small fw-semibold">Harga Jual</span>
                            <span class="fw-bold text-success" id="detail-price">-</span>
                        </li>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-0">
                            <span class="text-secondary small fw-semibold">Stok Tersedia</span>
                            <span class="fw-bold text-dark" id="detail-stock">-</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer bg-white px-4 py-3 border-0">
                <button type="button" class="btn btn-dark rounded-3 px-4 btn-sm fw-semibold" data-bs-dismiss="modal"
                    style="background: #111;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ================= 4. MODAL KONFIRMASI HAPUS ================= -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-center p-3">
            <div class="modal-body pt-4">
                <div class="avatar-icon mb-3 mx-auto d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle"
                    style="width: 60px; height: 60px; font-size: 28px;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Hapus Produk?</h5>
                <p class="text-muted small mb-4">Tindakan ini tidak dapat dibatalkan. Data produk akan dihapus permanen
                    dari sistem.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light rounded-3 px-3 py-2 btn-sm fw-semibold w-50"
                        data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="confirmDeleteBtn"
                        class="btn btn-danger rounded-3 px-3 py-2 btn-sm fw-bold w-50 shadow-sm">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Detail Batch FEFO -->
<div class="modal fade" id="modalBatchDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-layers text-warning me-2"></i> Detail Batch & FEFO: <span id="productNameTitle"
                        class="text-primary"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small">
                            <tr>
                                <th class="py-3">No. Batch</th>
                                <th class="py-3 text-center">Stok Awal</th>
                                <th class="py-3 text-center">Sisa Stok</th>
                                <th class="py-3">Expired Date</th>
                                <th class="py-3">Tanggal Masuk</th>
                            </tr>
                        </thead>
                        <tbody id="batchTableBody">
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 pb-3">
                <button type="button" class="btn btn-light border rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ================= JAVASCRIPT MODAL INTERAKSI ================= -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            document.getElementById('edit_product_name').value = this.getAttribute('data-name');
            document.getElementById('edit_price').value = this.getAttribute('data-price');
            document.getElementById('edit_stock').value = this.getAttribute('data-stock');
            document.getElementById('formEditProduct').action =
                "<?= base_url('admin/products/update/') ?>" + id;
        });
    });

    // Detail
    const detailButtons = document.querySelectorAll('.btn-detail');
    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('detail-name').innerText = this.getAttribute('data-name');
            document.getElementById('detail-price').innerText = this.getAttribute('data-price');
            document.getElementById('detail-stock').innerText = this.getAttribute('data-stock');
        });
    });

    // Hapus
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            document.getElementById('confirmDeleteBtn').href =
                "<?= base_url('admin/products/delete/') ?>" + id;
        });
    });
});
document.addEventListener("DOMContentLoaded", function() {
    const modalBatchDetail = document.getElementById('modalBatchDetail');
    if (modalBatchDetail) {
        modalBatchDetail.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget;
            let productId = button.getAttribute('data-id');
            let productName = button.getAttribute('data-name');

            document.getElementById('productNameTitle').innerText = productName;
            let tableBody = document.getElementById('batchTableBody');
            tableBody.innerHTML =
                `<tr><td colspan="5" class="text-center py-3 text-muted">Memuat data batch...</td></tr>`;

            fetch(`<?= base_url('admin/products/get-batches/') ?>/${productId}`)
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    if (!data || data.length === 0) {
                        tableBody.innerHTML =
                            `<tr><td colspan="5" class="text-center py-3 text-muted">Belum ada data batch aktif untuk produk ini.</td></tr>`;
                        return;
                    }

                    data.forEach((batch) => {
                        let row = `
                            <tr>
                                <td><span class="badge bg-light text-dark border px-2 py-1 font-monospace">${batch.batch_number}</span></td>
                                <td class="text-center">${batch.qty_initial} Unit</td>
                                <td class="text-center"><span class="badge bg-success bg-opacity-10 text-success px-2 py-1">${batch.qty_remaining} Unit</span></td>
                                <td><span class="text-danger fw-semibold"><i class="bi bi-calendar-event me-1"></i> ${batch.expired_date}</span></td>
                                <td class="text-muted small">${batch.created_at}</td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.innerHTML =
                        `<tr><td colspan="5" class="text-center py-3 text-danger">Gagal memuat data batch.</td></tr>`;
                });
        });
    }
});
</script>

<?= $this->endSection() ?>