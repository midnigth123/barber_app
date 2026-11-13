<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 w-100">
    <h1 class="mt-4"><?= esc($title); ?></h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Pengaturan Toko & Struk</li>
    </ol>

    <!-- Notifikasi Flashdata dengan Auto-Close 4 Detik -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" id="notificationAlert">
        <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            let alertElement = document.getElementById('notificationAlert');
            if (alertElement) {
                // Menggunakan fungsi bawaan Bootstrap Alert untuk menutup secara smooth
                let alert = bootstrap.Alert.getInstance(alertElement) || new bootstrap.Alert(
                    alertElement);
                alert.close();
            }
        }, 4000); // Waktu 4000 milidetik = 4 detik
    });
    </script>
    <?php endif; ?>

    <div class="card mb-4 border-0 shadow-sm w-100">
        <div class="card-header bg-white py-3">
            <i class="bi bi-shop text-primary me-1"></i>
            <strong>Form Konfigurasi Barbershop & Struk Kasir</strong>
        </div>
        <div class="card-body p-4">
            <form action="<?= base_url('admin/settings/shop/update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Barbershop / Toko</label>
                        <input type="text" name="shop_name" class="form-control"
                            value="<?= esc($setting['shop_name'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nomor Handphone / WhatsApp</label>
                        <input type="text" name="phone" class="form-control" value="<?= esc($setting['phone'] ?? '') ?>"
                            required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Akun Media Sosial (Instagram / FB)</label>
                        <input type="text" name="social_media" class="form-control"
                            value="<?= esc($setting['social_media'] ?? '') ?>"
                            placeholder="Contoh: @barbershop_official">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Persentase PPN (%)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="ppn_percentage" class="form-control"
                                value="<?= esc($setting['ppn_percentage'] ?? '0') ?>">
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Masukkan 0 jika transaksi bebas PPN.</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Lengkap Barbershop</label>
                    <textarea name="address" class="form-control" rows="3"
                        required><?= esc($setting['address'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Footer Struk Kasir (Catatan Bawah)</label>
                    <textarea name="receipt_footer" class="form-control" rows="3"
                        placeholder="Pesan terima kasih, syarat & ketentuan struk"><?= esc($setting['receipt_footer'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Logo Struk / Toko</label>
                    <?php if (!empty($setting['logo'])): ?>
                    <div class="mb-2">
                        <img src="<?= base_url('uploads/logo/' . $setting['logo']) ?>" alt="Logo Toko"
                            class="img-thumbnail" style="max-height: 80px;">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    <small class="text-muted">Format yang diizinkan: JPG, JPEG, PNG. Kosongkan jika tidak ingin
                        mengganti logo.</small>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>