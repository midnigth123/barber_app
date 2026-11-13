<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 w-100">
    <h1 class="mt-4 d-print-none"><?= esc($title); ?></h1>

    <!-- Breadcrumb disembunyikan saat print -->
    <ol class="breadcrumb mb-4 d-print-none">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan Waste</li>
    </ol>

    <!-- Form Filter & Tombol Cetak (Hilang saat diprint) -->
    <div class="card mb-4 d-print-none shadow-sm w-100">
        <div class="card-body">
            <form method="get" action="" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="<?= esc($start_date ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="<?= esc($end_date ?? '') ?>">
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    <a href="<?= base_url('admin/reports/waste') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                    <button type="button" onclick="window.print()" class="btn btn-danger ms-auto">
                        <i class="bi bi-file-pdf"></i> Cetak / PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- HANYA BAGIAN DI DALAM ID "printArea" INI YANG AKAN MUNCUL SAAT DICETAK -->
    <div id="printArea">
        <!-- KOP / HEADER LAPORAN (Hanya muncul saat dicetak) -->
        <div class="print-header d-none mb-4">
            <h2 style="margin: 0; font-size: 20px; font-weight: bold; text-transform: uppercase; text-align: center;">
                Laporan Waste Produk</h2>
            <p style="margin: 5px 0 0 0; font-size: 13px; text-align: center;">
                <?php if (!empty($start_date) && !empty($end_date)): ?>
                Periode: <?= esc($start_date); ?> s/d <?= esc($end_date); ?>
                <?php else: ?>
                Periode: Seluruh Waktu
                <?php endif; ?>
            </p>
            <hr style="border: 1.5px solid #000; margin-top: 12px; margin-bottom: 20px;">
        </div>

        <!-- Tabel Data Waste -->
        <div class="card mb-4 border-0 shadow-sm w-100">
            <div class="card-header bg-white d-print-none">
                <i class="bi bi-trash3 text-warning me-1"></i>
                Daftar Produk Waste (Rusak / Kadaluarsa)
            </div>
            <div class="card-body p-0 p-md-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle w-100">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Kode Waste</th>
                                <th>Tanggal</th>
                                <th>Nama Produk</th> <!-- Diubah dari ID Produk -->
                                <th class="text-center">Jumlah (Qty)</th>
                                <th>Alasan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($wastes)): ?>
                            <?php $no = 1; foreach ($wastes as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><?= esc($row['waste_code'] ?? '-'); ?></td>
                                <td><?= esc($row['waste_date'] ?? '-'); ?></td>
                                <td><?= esc($row['product_name'] ?? '-'); ?></td>
                                <!-- Menggunakan product_name hasil join -->
                                <td class="text-center"><?= esc($row['qty_waste'] ?? 0); ?></td>
                                <td><?= esc($row['reason'] ?? '-'); ?></td>
                                <td><?= esc($row['notes'] ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada data laporan waste pada
                                    rentang tanggal tersebut.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- BAGIAN TERTANDA / TANDA TANGAN (Hanya muncul saat dicetak) -->
                <div class="print-signature d-none mt-5">
                    <table class="signature-table" style="width: 100%; border: none; margin-top: 40px;">
                        <tr>
                            <td style="border: none; width: 65%;"></td>
                            <td style="border: none; text-align: center; width: 35%;">
                                <p style="margin-bottom: 5px; font-size: 12px;">Padang, <?= date('d F Y'); ?></p>
                                <p style="margin-bottom: 65px; font-size: 12px; font-weight: bold;">Mengetahui,<br>Admin
                                    /
                                    Owner</p>
                                <p style="font-size: 12px; font-weight: bold; text-decoration: underline; margin: 0;">
                                    Dyan
                                    Mardinata Putra</p>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- CSS TAMBAHAN AGAR LEBAR PENUH & BERSIH SAAT CETAK -->
<style>
.container-fluid {
    max-width: 100% !important;
}

@media print {

    /* Sembunyikan SEMUA elemen di luar #printArea secara mutlak (termasuk Dashboard Admin & nama login) */
    body * {
        visibility: hidden !important;
    }

    /* Tampilkan kembali hanya #printArea dan isinya */
    #printArea,
    #printArea * {
        visibility: visible !important;
    }

    /* Posisikan ulang area cetak ke sudut kiri atas kertas */
    #printArea {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Reset background dan margin halaman cetak */
    body {
        background-color: #fff !important;
        color: #000 !important;
        font-family: Arial, sans-serif !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
        width: 100% !important;
    }

    .card-body {
        padding: 0 !important;
    }

    /* Tampilkan kop laporan dan tanda tangan */
    .print-header {
        display: block !important;
    }

    .print-signature {
        display: block !important;
        page-break-inside: avoid;
    }

    /* Styling tabel agar rapi saat dicetak */
    table.table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    table.table th,
    table.table td {
        border: 1px solid #000 !important;
        font-size: 10pt !important;
        color: #000 !important;
        padding: 6px 8px !important;
    }

    table.table th {
        background-color: #e9ecef !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        text-align: center;
    }

    table.signature-table td {
        border: none !important;
    }
}
</style>
<?= $this->endSection() ?>