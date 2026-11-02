<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-3">
    <!-- Header & Navigasi -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-file-earmark-text-fill text-warning me-2"></i> Laporan Transaksi
            </h3>
            <p class="text-muted small mb-0">Rekapitulasi seluruh riwayat transaksi penjualan dan layanan barbershop.
            </p>
        </div>
        <a href="<?= base_url('admin/pos') ?>" class="btn btn-dark btn-sm rounded-3 px-3 fw-semibold"
            style="background-color: #111;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Kasir
        </a>
    </div>

    <!-- Alert Notifikasi Sukses/Gagal -->
    <?php if (session()->getFlashdata('success')): ?>
    <div id="autoCloseAlert" class="alert alert-success alert-dismissible fade show rounded-3 small" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Kartu Statistik Singkat -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-4 border-warning bg-white">
                <span class="text-muted small fw-semibold">Total Pendapatan Keseluruhan (Aktif)</span>
                <h3 class="fw-bold text-dark mt-1 mb-0">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 border-start border-4 border-dark bg-white">
                <span class="text-muted small fw-semibold">Total Transaksi Berhasil</span>
                <!-- Menggunakan variabel $successCount agar berkurang otomatis saat ada yang dibatalkan -->
                <h3 class="fw-bold text-dark mt-1 mb-0"><?= $successCount ?> <span
                        class="fs-6 text-muted fw-normal">Invoice</span></h3>
            </div>
        </div>
    </div>

    <!-- Tabel Data Transaksi -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small">
                    <tr>
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Metode Pembayaran</th>
                        <th>Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data transaksi yang tercatat.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($transactions as $trx): 
                            $isCancelled = (isset($trx['status']) && $trx['status'] === 'cancelled');
                        ?>
                    <tr class="<?= $isCancelled ? 'table-danger text-muted' : '' ?>">
                        <td>
                            <a href="javascript:void(0);" onclick="showTransactionDetail(<?= $trx['id'] ?>)"
                                class="fw-bold <?= $isCancelled ? 'text-danger text-decoration-line-through' : 'text-dark' ?>"
                                title="Klik untuk lihat detail">
                                <?= $trx['invoice_code'] ?>
                            </a>
                            <?php if ($isCancelled): ?>
                            <div><span class="badge bg-danger text-white" style="font-size: 8px;">DIBATALKAN</span>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td class="small <?= $isCancelled ? 'text-decoration-line-through' : 'text-muted' ?>">
                            <?= $trx['created_at'] ?></td>
                        <td class="<?= $isCancelled ? 'text-decoration-line-through' : 'fw-semibold' ?>">
                            <?= $trx['customer_name'] ?></td>
                        <td>
                            <span class="badge <?= $isCancelled ? 'bg-secondary' : 'bg-secondary' ?> text-uppercase"
                                style="font-size: 10px;"><?= $trx['payment_method'] ?></span>
                        </td>
                        <td
                            class="fw-bold <?= $isCancelled ? 'text-decoration-line-through text-danger' : 'text-success' ?>">
                            Rp <?= number_format($trx['total_amount'], 0, ',', '.') ?></td>
                        <td class="text-center">
                            <?php if (!$isCancelled): ?>
                            <div class="btn-group" role="group">
                                <a href="<?= base_url('admin/pos/receipt/' . $trx['id']) ?>" target="_blank"
                                    class="btn btn-sm btn-light border rounded-3 px-2 py-1 text-dark me-1"
                                    title="Cetak Struk">
                                    <i class="bi bi-printer text-warning"></i>
                                </a>
                                <button type="button"
                                    onclick="openCancelModal(<?= $trx['id'] ?>, '<?= $trx['invoice_code'] ?>')"
                                    class="btn btn-sm btn-outline-danger rounded-3 px-2 py-1"
                                    title="Batalkan Transaksi">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                            <?php else: ?>
                            <span class="small text-danger fst-italic"
                                title="<?= $trx['cancellation_reason'] ?>">Alasan:
                                <?= esc($trx['cancellation_reason']) ?></span>
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

<!-- MODAL DETAIL TRANSAKSI -->
<div class="modal fade" id="detailTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2">
                    <i class="bi bi-receipt text-warning"></i> Detail Transaksi: <span id="modalInvoiceCode"
                        class="text-warning"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="bg-white p-3 rounded-3 shadow-sm mb-3 small">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Status:</span>
                        <span id="modalStatus" class="fw-bold"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Tanggal:</span>
                        <span id="modalDate" class="fw-semibold"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Nama Customer:</span>
                        <span id="modalCustomer" class="fw-semibold"></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Metode Pembayaran:</span>
                        <span id="modalPayment" class="fw-semibold text-uppercase"></span>
                    </div>
                </div>

                <div class="table-responsive bg-white rounded-3 shadow-sm p-3">
                    <table class="table table-sm align-middle mb-0 small">
                        <thead>
                            <tr class="text-secondary border-bottom">
                                <th>Item / Layanan</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalItemList">
                            <!-- Data item dimuat secara dinamis -->
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center bg-dark text-white p-3 rounded-3 mt-3">
                    <span class="fw-semibold small">Total Pembayaran:</span>
                    <span id="modalTotalAmount" class="fw-bold text-warning fs-5"></span>
                </div>
            </div>
            <div class="modal-footer bg-white px-4 py-3 border-0">
                <a href="#" id="modalPrintBtn" target="_blank" class="btn btn-dark w-100 rounded-3 py-2 fw-bold"
                    style="background-color: #111;">
                    <i class="bi bi-printer-fill me-2 text-warning"></i> Cetak Ulang Struk
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FORM ALASAN PEMBATALAN -->
<div class="modal fade" id="cancelTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <form id="cancelForm" method="post" class="w-100">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-danger text-white px-4 py-3 border-0">
                    <h5 class="modal-title fw-bold fs-6 d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i> Batalkan Transaksi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <p class="text-muted small mb-3">Anda akan membatalkan invoice <strong id="cancelInvoiceLabel"
                            class="text-dark"></strong>. Stok produk yang terkait akan dikembalikan secara otomatis.</p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Alasan Pembatalan <span
                                class="text-danger">*</span></label>
                        <textarea name="cancellation_reason" class="form-control rounded-3 py-2 small" rows="3" required
                            placeholder="Contoh: Salah input pesanan / Customer batal..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-white px-4 py-3 border-0">
                    <button type="button" class="btn btn-light rounded-3 px-3 btn-sm fw-semibold"
                        data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-3 btn-sm fw-bold">Ya, Batalkan
                        Transaksi</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript Script -->
<script>
function showTransactionDetail(id) {
    fetch('<?= base_url('admin/reports/transaction-detail/') ?>' + id)
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                document.getElementById('modalInvoiceCode').innerText = data.transaction.invoice_code;
                document.getElementById('modalDate').innerText = data.transaction.created_at;
                document.getElementById('modalCustomer').innerText = data.transaction.customer_name;
                document.getElementById('modalPayment').innerText = data.transaction.payment_method;
                document.getElementById('modalTotalAmount').innerText = 'Rp ' + Number(data.transaction
                    .total_amount).toLocaleString('id-ID');

                let statusBadge = data.transaction.status === 'cancelled' ?
                    '<span class="badge bg-danger">Dibatalkan</span>' :
                    '<span class="badge bg-success">Berhasil</span>';
                document.getElementById('modalStatus').innerHTML = statusBadge;

                document.getElementById('modalPrintBtn').href = '<?= base_url('admin/pos/receipt/') ?>' + id;

                let itemListHTML = '';
                data.details.forEach(item => {
                    itemListHTML += `
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">${item.item_name}</div>
                                <span class="badge bg-secondary" style="font-size: 9px;">${item.item_type}</span>
                            </td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-end fw-semibold">Rp ${item.subtotal}</td>
                        </tr>
                    `;
                });
                document.getElementById('modalItemList').innerHTML = itemListHTML;

                var myModal = new bootstrap.Modal(document.getElementById('detailTransactionModal'));
                myModal.show();
            }
        });
}

function openCancelModal(id, invoiceCode) {
    document.getElementById('cancelInvoiceLabel').innerText = invoiceCode;
    document.getElementById('cancelForm').action = '<?= base_url('admin/reports/cancel-transaction/') ?>' + id;

    var cancelModal = new bootstrap.Modal(document.getElementById('cancelTransactionModal'));
    cancelModal.show();
}
document.addEventListener("DOMContentLoaded", function() {
    // Timer untuk menghilangkan alert otomatis dalam 3 detik (3000 milidetik)
    const alertElement = document.getElementById('autoCloseAlert');
    if (alertElement) {
        setTimeout(function() {
            // Menggunakan fungsi transisi fade Bootstrap
            let alert = new bootstrap.Alert(alertElement);
            alert.close();
        }, 3000);
    }
});
</script>

<?= $this->endSection() ?>