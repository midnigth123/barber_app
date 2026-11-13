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
            <h3 class="fw-bold mb-1"><i class="bi bi-percent text-warning me-2"></i> Pengaturan Komisi Layanan</h3>
            <p class="text-muted small mb-0">Atur persentase pembagian komisi kapster untuk setiap jenis jasa layanan.
            </p>
        </div>
    </div>

    <!-- Tabel Komisi Layanan -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small">
                    <tr>
                        <th class="py-3">No</th>
                        <th class="py-3">Nama Layanan</th>
                        <th class="py-3">Harga Dasar (Rp)</th>
                        <th class="py-3" style="width: 25%;">Persentase Komisi (%)</th>
                        <th class="py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($services)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data layanan.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($services as $index => $srv): ?>
                    <tr>
                        <td class="fw-semibold text-secondary"><?= $index + 1 ?></td>
                        <td>
                            <span class="fw-bold text-dark"><?= esc($srv['service_name']) ?></span>
                        </td>
                        <td>
                            <span class="text-muted">Rp <?= number_format($srv['price'], 0, ',', '.') ?></span>
                        </td>
                        <td>
                            <!-- Form Update per Baris -->
                            <form action="<?= base_url('admin/services/commissions/update/' . $srv['id']) ?>"
                                method="POST" id="form-comm-<?= $srv['id'] ?>">
                                <?= csrf_field() ?>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" name="commission_rate"
                                        value="<?= esc($srv['commission_rate'] ?? 30.00) ?>"
                                        class="form-control rounded-start-3 text-center fw-semibold" required>
                                    <span class="input-group-text bg-light text-secondary rounded-end-3">%</span>
                                </div>
                            </form>
                        </td>
                        <td class="text-center">
                            <button type="submit" form="form-comm-<?= $srv['id'] ?>"
                                class="btn btn-sm btn-dark text-warning rounded-3 px-3 py-1 shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
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