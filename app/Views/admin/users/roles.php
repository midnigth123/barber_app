<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-3">

    <!-- Notifikasi Flash Message dengan Auto-Close 4 Detik -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert"
        id="notificationAlert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            let alertElement = document.getElementById('notificationAlert');
            if (alertElement) {
                let alert = bootstrap.Alert.getInstance(alertElement) || new bootstrap.Alert(
                    alertElement);
                alert.close();
            }
        }, 4000); // Waktu 4000 milidetik = 4 detik
    });
    </script>
    <?php endif; ?>

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-shield-lock-fill text-warning me-2"></i> Role Akses Pengguna</h3>
            <p class="text-muted small mb-0">Daftar tingkat hak akses (role) dan otoritas dalam sistem Barber POS.</p>
        </div>
    </div>

    <!-- Tabel Daftar Role -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small">
                    <tr>
                        <th>Nama Role</th>
                        <th>Deskripsi Hak Akses</th>
                        <th class="text-center">Jumlah User</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $index => $role): ?>
                    <tr>
                        <td>
                            <span class="badge bg-dark text-uppercase text-warning px-2 py-1" style="font-size: 11px;">
                                <?= $role['name'] ?>
                            </span>
                        </td>
                        <td>
                            <span class="text-secondary small"><?= $role['description'] ?></span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1"><?= $role['users_count'] ?>
                                Akun</span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-dark"
                                data-bs-toggle="modal" data-bs-target="#modalPermission<?= $index ?>"
                                title="Atur Hak Akses">
                                <i class="bi bi-gear text-warning"></i> Kelola Permission
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL KELOLA PERMISSION UNTUK SETIAP ROLE -->
<?php foreach ($roles as $index => $role): ?>
<div class="modal fade" id="modalPermission<?= $index ?>" tabindex="-1"
    aria-labelledby="modalPermissionLabel<?= $index ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="<?= base_url('admin/users/roles/update/' . $role['name']) ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalPermissionLabel<?= $index ?>">
                        <i class="bi bi-key text-warning me-2"></i> Atur Hak Akses: <span
                            class="text-uppercase text-dark"><?= $role['name'] ?></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="text-muted small mb-3">Centang menu atau modul yang diizinkan untuk diakses oleh role ini:
                    </p>

                    <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                        <label class="list-group-item d-flex align-items-center py-2">
                            <input class="form-check-input me-3" type="checkbox" name="permissions[]" value="dashboard"
                                checked>
                            <div>
                                <span class="fw-semibold d-block">Dashboard</span>
                                <small class="text-muted">Melihat ringkasan statistik dan grafik.</small>
                            </div>
                        </label>
                        <label class="list-group-item d-flex align-items-center py-2">
                            <input class="form-check-input me-3" type="checkbox" name="permissions[]" value="pos"
                                checked>
                            <div>
                                <span class="fw-semibold d-block">Kasir POS (Transaksi)</span>
                                <small class="text-muted">Melakukan transaksi pembayaran & cetak struk.</small>
                            </div>
                        </label>
                        <label class="list-group-item d-flex align-items-center py-2">
                            <input class="form-check-input me-3" type="checkbox" name="permissions[]" value="services"
                                <?= $role['name'] === 'admin' ? 'checked' : '' ?>>
                            <div>
                                <span class="fw-semibold d-block">Kelola Layanan</span>
                                <small class="text-muted">Tambah, edit, hapus data jasa layanan cukur.</small>
                            </div>
                        </label>
                        <label class="list-group-item d-flex align-items-center py-2">
                            <input class="form-check-input me-3" type="checkbox" name="permissions[]" value="products"
                                <?= $role['name'] === 'admin' ? 'checked' : '' ?>>
                            <div>
                                <span class="fw-semibold d-block">Kelola Produk</span>
                                <small class="text-muted">Kelola stok barang dan produk pomade/aksesoris.</small>
                            </div>
                        </label>
                        <label class="list-group-item d-flex align-items-center py-2">
                            <input class="form-check-input me-3" type="checkbox" name="permissions[]" value="reports"
                                <?= $role['name'] !== 'kasir' ? 'checked' : '' ?>>
                            <div>
                                <span class="fw-semibold d-block">Laporan Transaksi</span>
                                <small class="text-muted">Melihat rekap pendapatan & komisi kapster.</small>
                            </div>
                        </label>
                        <label class="list-group-item d-flex align-items-center py-2">
                            <input class="form-check-input me-3" type="checkbox" name="permissions[]" value="users"
                                <?= $role['name'] === 'admin' ? 'checked' : '' ?>>
                            <div>
                                <span class="fw-semibold d-block">Manajemen User & Log</span>
                                <small class="text-muted">Mengatur akun pengguna dan riwayat log aktivitas.</small>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-3">
                    <button type="button" class="btn btn-light border rounded-3 px-3"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-3 px-4 text-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?= $this->endSection() ?>