<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-4">

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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark">Manajemen Stok Opname</h1>
            <p class="text-secondary small mb-0">Kelola jadwal, sesi, dan penyesuaian stok fisik produk berbasis FEFO &
                Batch.</p>
        </div>
        <!-- Tombol untuk mulai sesi opname baru -->
        <a href="<?= base_url('admin/products/stock-opname/start') ?>"
            class="btn btn-dark rounded-3 shadow-sm px-4 py-2 fw-semibold">
            <i class="bi bi-play-circle me-1"></i> Mulai Stok Opname
        </a>
    </div>

    <!-- Notifikasi Error (jika ada) tetap dipertahankan pakai alert biasa -->
    <?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="ps-4">Kode Opname</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($opnameList)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-folder2-open display-6 d-block mb-2 text-secondary"></i>
                                Belum ada sesi stok opname. Klik tombol <strong>"Mulai Stok Opname"</strong> di atas.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($opnameList as $row): ?>
                        <tr>
                            <td class="ps-4 fw-semibold text-dark">
                                <span class="font-monospace text-primary"><?= esc($row['opname_code']) ?></span>
                            </td>
                            <td>
                                <span class="text-dark small">
                                    <i class="bi bi-calendar-event me-1 text-muted"></i> <?= esc($row['start_date']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="text-dark small">
                                    <i class="bi bi-calendar-check me-1 text-muted"></i>
                                    <?= esc($row['end_date'] ?? 'Belum Selesai') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($row['status'] === 'completed'): ?>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Selesai</span>
                                <?php else: ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Sedang
                                    Berjalan</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <?php if ($row['status'] === 'progress'): ?>
                                <a href="<?= base_url('admin/products/stock-opname/process/' . $row['id']) ?>"
                                    class="btn btn-sm btn-primary rounded-3 px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Lanjut Opname
                                </a>
                                <?php else: ?>
                                <!-- Tombol Modal Detail -->
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 px-3 btn-detail"
                                    data-id="<?= $row['id'] ?>" data-bs-toggle="modal"
                                    data-bs-target="#modalDetailOpname">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </button>
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
</div>

<!-- BOOTSTRAP MODAL DETAIL OPNAME -->
<div class="modal fade" id="modalDetailOpname" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalDetailLabel">Detail Hasil Stok Opname</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Loading Spinner -->
                <div id="modal-loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted small">Memuat data detail...</p>
                </div>

                <!-- Konten Detail -->
                <div id="modal-content-data" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Kode Sesi:</span>
                            <strong class="font-monospace text-primary fs-6" id="lbl-code"></strong>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <span class="text-muted small d-block">Tanggal Selesai:</span>
                            <strong class="text-dark" id="lbl-end-date"></strong>
                        </div>
                    </div>
                    <hr class="text-muted opacity-25">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light text-uppercase fs-7 text-secondary">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>No. Batch</th>
                                    <th>Expired Date</th>
                                    <th class="text-center">Stok Sistem</th>
                                    <th class="text-center">Stok Fisik Real</th>
                                    <th class="text-center">Selisih</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-detail-body">
                                <!-- Data dimasukkan via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4">
                <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT AJAX MODAL -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailButtons = document.querySelectorAll('.btn-detail');

    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const opnameId = this.getAttribute('data-id');

            // Reset tampilan modal ke mode loading
            document.getElementById('modal-loading').style.display = 'block';
            document.getElementById('modal-content-data').style.display = 'none';
            document.getElementById('tbl-detail-body').innerHTML = '';

            // Ambil data via fetch JSON
            fetch('<?= base_url('admin/products/stock-opname/detail/') ?>' + opnameId)
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        document.getElementById('lbl-code').innerText = data.opnameSession
                            .opname_code;
                        document.getElementById('lbl-end-date').innerText = data
                            .opnameSession.end_date ?? '-';

                        let rows = '';
                        data.products.forEach(product => {
                            if (product.batches && product.batches.length > 0) {
                                product.batches.forEach(batch => {
                                    const stokSistem = parseInt(batch
                                        .system_stock ?? batch
                                        .qty_remaining) || 0;
                                    const stokFisik = parseInt(batch
                                        .qty_remaining) || 0;

                                    const selisih = stokFisik - stokSistem;
                                    let badgeColor = selisih === 0 ?
                                        'bg-success' : (selisih > 0 ?
                                            'bg-primary' : 'bg-danger');
                                    const tandaSelisih = selisih > 0 ?
                                        `+${selisih}` : selisih;

                                    rows += `<tr>
                                            <td class="fw-semibold">${product.product_name}</td>
                                            <td><span class="badge bg-light text-dark font-monospace">${batch.batch_number}</span></td>
                                            <td><i class="bi bi-calendar-event me-1 text-muted"></i>${batch.expired_date}</td>
                                            <td class="text-center fw-semibold text-secondary">${stokSistem}</td>
                                            <td class="text-center fw-bold text-dark">${stokFisik}</td>
                                            <td class="text-center">
                                                <span class="badge ${badgeColor}">${tandaSelisih}</span>
                                            </td>
                                        </tr>`;
                                });
                            }
                        });

                        if (rows === '') {
                            rows =
                                `<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data batch untuk produk ini.</td></tr>`;
                        }

                        document.getElementById('tbl-detail-body').innerHTML = rows;
                        document.getElementById('modal-loading').style.display = 'none';
                        document.getElementById('modal-content-data').style.display =
                            'block';
                    } else {
                        alert(data.message || 'Gagal memuat detail.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data.');
                });
        });
    });
});
</script>
<?= $this->endSection() ?>