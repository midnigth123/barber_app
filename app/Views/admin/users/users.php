<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-3">

    <!-- Notifikasi Berhasil (Flashdata) -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert"
        style="background-color: #d1e7dd; color: #0f5132;">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Header & Tombol Tambah -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-people-fill text-warning me-2"></i> Manajemen Data User</h3>
            <p class="text-muted small mb-0">Kelola daftar akun administrator, kasir, dan kapster barbershop.</p>
        </div>
        <button type="button" class="btn btn-dark btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal"
            data-bs-target="#addUserModal" style="background-color: #111;">
            <i class="bi bi-plus-lg me-1 text-warning"></i> Tambah User Baru
        </button>
    </div>

    <!-- Tabel Data User -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small">
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Username / Email</th>
                        <th>Role Akses</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data user terdaftar.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($users as $usr): ?>
                    <tr>
                        <td class="fw-bold text-dark"><?= $usr['name'] ?></td>
                        <td class="small text-muted">
                            <div><?= $usr['username'] ?></div>
                            <div style="font-size: 11px;"><?= $usr['email'] ?? '-' ?></div>
                        </td>
                        <td>
                            <span class="badge bg-dark text-uppercase text-warning"
                                style="font-size: 10px;"><?= $usr['role'] ?></span>
                        </td>
                        <td>
                            <?php if (isset($usr['is_active']) && $usr['is_active'] == 0): ?>
                            <span class="badge bg-secondary" style="font-size: 10px;">NONAKTIF</span>
                            <?php else: ?>
                            <span class="badge bg-success" style="font-size: 10px;">AKTIF</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <!-- Tombol Trigger Modal Edit -->
                            <button type="button" class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-dark me-1"
                                data-bs-toggle="modal" data-bs-target="#editUserModal<?= $usr['id'] ?? 0 ?>"
                                title="Edit User">
                                <i class="bi bi-pencil text-warning"></i>
                            </button>

                            <!-- Tombol Status (Aktif / Nonaktif Dinamis) -->
                            <?php if (isset($usr['is_active']) && $usr['is_active'] == 0): ?>
                            <button type="button" class="btn btn-sm btn-outline-success rounded-3 px-2 py-1"
                                data-bs-toggle="modal" data-bs-target="#toggleUserModal<?= $usr['id'] ?? 0 ?>"
                                title="Aktifkan Kembali">
                                <i class="bi bi-check-circle"></i>
                            </button>
                            <?php else: ?>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-2 py-1"
                                data-bs-toggle="modal" data-bs-target="#toggleUserModal<?= $usr['id'] ?? 0 ?>"
                                title="Nonaktifkan User">
                                <i class="bi bi-trash"></i>
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

<!-- ========================================== -->
<!-- KUMPULAN MODAL (Ditarik Keluar dari Tabel)  -->
<!-- ========================================== -->

<!-- Modal Tambah User Baru -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('admin/users/store') ?>" method="post" class="w-100">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                    <h5 class="modal-title fw-bold fs-6"><i class="bi bi-person-plus-fill text-warning me-2"></i> Tambah
                        User Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light text-start">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control rounded-3 py-2 small" required
                            placeholder="Contoh: Budi Santoso">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Username</label>
                        <input type="text" name="username" class="form-control rounded-3 py-2 small" required
                            placeholder="Contoh: kasir01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Email</label>
                        <input type="email" name="email" class="form-control rounded-3 py-2 small"
                            placeholder="email@domain.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Role Akses</label>
                        <select name="role" class="form-select rounded-3 py-2 small" required>
                            <option value="admin">Admin / Owner</option>
                            <option value="cashier">Kasir</option>
                            <option value="barber">Barber / Kapster</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Password</label>
                        <input type="password" name="password" class="form-control rounded-3 py-2 small" required
                            placeholder="••••••••">
                    </div>
                </div>
                <div class="modal-footer bg-white px-4 py-3 border-0">
                    <button type="button" class="btn btn-light rounded-3 px-3 btn-sm fw-semibold"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-3 px-3 btn-sm fw-bold"
                        style="background-color: #111;">Simpan User</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit & Konfirmasi Status (Dirender Melalui Looping di Luar Tabel) -->
<?php if (!empty($users)): ?>
<?php foreach ($users as $usr): ?>

<!-- Modal Edit User -->
<div class="modal fade" id="editUserModal<?= $usr['id'] ?? 0 ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('admin/users/update/' . ($usr['id'] ?? 0)) ?>" method="post" class="w-100">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                    <h5 class="modal-title fw-bold fs-6"><i class="bi bi-pencil-square text-warning me-2"></i> Edit
                        User: <?= $usr['name'] ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light text-start">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Nama Lengkap</label>
                        <input type="text" name="name" value="<?= $usr['name'] ?>"
                            class="form-control rounded-3 py-2 small" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Username</label>
                        <input type="text" name="username" value="<?= $usr['username'] ?>"
                            class="form-control rounded-3 py-2 small" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Email</label>
                        <input type="email" name="email" value="<?= $usr['email'] ?? '' ?>"
                            class="form-control rounded-3 py-2 small">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Role Akses</label>
                        <select name="role" class="form-select rounded-3 py-2 small" required>
                            <option value="admin" <?= ($usr['role'] == 'admin') ? 'selected' : '' ?>>Admin / Owner
                            </option>
                            <option value="cashier" <?= ($usr['role'] == 'cashier') ? 'selected' : '' ?>>Kasir</option>
                            <option value="barber" <?= ($usr['role'] == 'barber') ? 'selected' : '' ?>>Barber / Kapster
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Password Baru <span
                                class="text-muted fw-normal">(Kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" class="form-control rounded-3 py-2 small"
                            placeholder="••••••••">
                    </div>
                </div>
                <div class="modal-footer bg-white px-4 py-3 border-0">
                    <button type="button" class="btn btn-light rounded-3 px-3 btn-sm fw-semibold"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-3 px-3 btn-sm fw-bold"
                        style="background-color: #111;">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Status (Aktif / Nonaktif) -->
<div class="modal fade" id="toggleUserModal<?= $usr['id'] ?? 0 ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-center">
            <div class="modal-body p-4 bg-white">
                <?php if (isset($usr['is_active']) && $usr['is_active'] == 0): ?>
                <div class="mb-3 text-success mt-2">
                    <i class="bi bi-check-circle fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Aktifkan Kembali?</h5>
                <p class="text-muted small mb-4">Apakah Anda yakin ingin mengaktifkan akun
                    <strong><?= $usr['name'] ?></strong> agar bisa login kembali?</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light btn-sm rounded-3 px-3 fw-semibold"
                        data-bs-dismiss="modal">Batal</button>
                    <a href="<?= base_url('admin/users/delete/' . ($usr['id'] ?? 0)) ?>"
                        class="btn btn-success btn-sm rounded-3 px-3 fw-bold">Ya, Aktifkan</a>
                </div>
                <?php else: ?>
                <div class="mb-3 text-warning mt-2">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Nonaktifkan User?</h5>
                <p class="text-muted small mb-4">Apakah Anda yakin ingin menonaktifkan akun
                    <strong><?= $usr['name'] ?></strong>? User tidak akan bisa login ke sistem.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light btn-sm rounded-3 px-3 fw-semibold"
                        data-bs-dismiss="modal">Batal</button>
                    <a href="<?= base_url('admin/users/delete/' . ($usr['id'] ?? 0)) ?>"
                        class="btn btn-warning btn-sm rounded-3 px-3 fw-bold text-dark">Ya, Nonaktifkan</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php endforeach; ?>
<?php endif; ?>

<!-- Script Auto-Close Alert -->
<script>
setTimeout(function() {
    let alertEl = document.querySelector('.alert-success');
    if (alertEl) {
        let alert = new bootstrap.Alert(alertEl);
        alert.close();
    }
}, 3000);
</script>

<?= $this->endSection() ?>