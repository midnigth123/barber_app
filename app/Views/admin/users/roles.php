<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-3">

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
                    <?php foreach ($roles as $role): ?>
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

<?= $this->endSection() ?>