<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 w-100">
    <h1 class="mt-4 d-print-none"><?= esc($title); ?></h1>


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
                    <a href="<?= base_url('admin/reports/expenses') ?>" class="btn btn-secondary">
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
                Laporan Pengeluaran</h2>
            <p style="margin: 5px 0 0 0; font-size: 13px; text-align: center;">
                <?php if (!empty($start_date) && !empty($end_date)): ?>
                Periode: <?= esc($start_date); ?> s/d <?= esc($end_date); ?>
                <?php else: ?>
                Periode: Seluruh Waktu
                <?php endif; ?>
            </p>
            <hr style="border: 1.5px solid #000; margin-top: 12px; margin-bottom: 20px;">
        </div>

        <!-- Tabel Data Pengeluaran -->
        <div class="card mb-4 border-0 shadow-sm w-100">
            <div class="card-header bg-white d-print-none">
                <i class="bi bi-wallet2 text-warning me-1"></i>
                Daftar Riwayat Pengeluaran
            </div>
            <div class="card-body p-0 p-md-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle w-100">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Kode Pengeluaran</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th class="text-end">Nominal (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_expense = 0; ?>
                            <?php if (!empty($expenses)): ?>
                            <?php $no = 1; foreach ($expenses as $row): ?>
                            <?php $total_expense += $row['amount']; ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><?= esc($row['expense_code'] ?? '-'); ?></td>
                                <td><?= esc($row['expense_date'] ?? '-'); ?></td>
                                <td><?= esc($row['category'] ?? '-'); ?></td>
                                <td><?= esc($row['description'] ?? '-'); ?></td>
                                <td class="text-end">Rp <?= number_format($row['amount'] ?? 0, 0, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <!-- Baris Total -->
                            <tr class="fw-bold">
                                <td colspan="5" class="text-end">Total Pengeluaran:</td>
                                <td class="text-end">Rp <?= number_format($total_expense, 0, ',', '.'); ?></td>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Tidak ada data laporan pengeluaran
                                    pada rentang tanggal tersebut.</td>
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
                                    / Owner</p>
                                <p style="font-size: 12px; font-weight: bold; text-decoration: underline; margin: 0;">
                                    Dyan Mardinata Putra</p>
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
    body * {
        visibility: hidden !important;
    }

    #printArea,
    #printArea * {
        visibility: visible !important;
    }

    #printArea {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

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

    .print-header {
        display: block !important;
    }

    .print-signature {
        display: block !important;
        page-break-inside: avoid;
    }

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