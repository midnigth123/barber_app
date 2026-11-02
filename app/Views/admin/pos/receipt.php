<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Struk Pembayaran' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">

    <style>
    :root {
        --primary-dark: #111111;
        --accent-gold: #d4af37;
        --bg-body: #f4f7f6;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-body);
        color: var(--primary-dark);
    }

    .receipt-container {
        max-width: 400px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(212, 175, 55, 0.2);
    }

    .receipt-header {
        text-align: center;
        border-bottom: 2px dashed #eaeaea;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .brand-badge {
        background: linear-gradient(135deg, var(--accent-gold) 0%, #aa771c 100%);
        color: #111;
        font-weight: 700;
        font-size: 10px;
        padding: 4px 10px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-top: 6px;
    }

    .receipt-footer {
        text-align: center;
        border-top: 2px dashed #eaeaea;
        padding-top: 20px;
        margin-top: 20px;
        font-size: 12px;
        color: #666;
    }

    @media print {
        body {
            background: #ffffff;
        }

        .no-print {
            display: none !important;
        }

        .receipt-container {
            box-shadow: none;
            margin: 0;
            max-width: 100%;
            padding: 10px;
            border: none;
        }
    }
    </style>
</head>

<body>

    <div class="receipt-container">
        <!-- Header Struk -->
        <div class="receipt-header">
            <h4 class="fw-bold mb-1" style="letter-spacing: 0.5px;">BARBER POS</h4>
            <p class="text-muted small mb-1">Jl. Contoh Alamat No. 123, Kota Anda</p>
            <span class="brand-badge">LUXURY BARBERSHOP</span>
        </div>

        <!-- Info Transaksi -->
        <div class="mb-3 small">
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">No. Invoice:</span>
                <span class="fw-bold text-dark"><?= $transaction['invoice_code'] ?></span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Tanggal:</span>
                <span><?= $transaction['created_at'] ?></span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Customer:</span>
                <span class="fw-bold"><?= $transaction['customer_name'] ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Kasir:</span>
                <span><?= session()->get('name') ?? 'Admin' ?></span>
            </div>
        </div>

        <!-- Detail Item -->
        <div class="table-responsive mb-3">
            <table class="table table-sm small mb-0 align-middle">
                <thead>
                    <tr class="border-bottom text-secondary">
                        <th class="py-2">Item</th>
                        <th class="text-center py-2">Qty</th>
                        <th class="text-end py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $row): ?>
                    <tr>
                        <td class="py-2">
                            <?php 
                                if($row['item_type'] == 'service') {
                                    $sModel = new \App\Models\ServiceModel();
                                    $itemData = $sModel->find($row['item_id']);
                                    echo $itemData['name'] ?? 'Layanan Cukur';
                                } else {
                                    $pModel = new \App\Models\ProductModel();
                                    $itemData = $pModel->find($row['item_id']);
                                    echo $itemData['name'] ?? 'Produk';
                                }
                            ?>
                        </td>
                        <td class="text-center py-2"><?= $row['quantity'] ?></td>
                        <td class="text-end py-2 fw-semibold">Rp
                            <?= number_format($row['price'] * $row['quantity'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Total Pembayaran -->
        <div class="border-top pt-3 mb-4 small">
            <div class="d-flex justify-content-between fw-bold fs-6 text-dark mb-1">
                <span>Total Belanja:</span>
                <span class="text-dark">Rp <?= number_format($transaction['total_amount'], 0, ',', '.') ?></span>
            </div>
            <div class="d-flex justify-content-between text-muted" style="font-size: 12px;">
                <span>Metode Pembayaran:</span>
                <span class="fw-semibold text-uppercase"><?= $transaction['payment_method'] ?></span>
            </div>
        </div>

        <!-- Footer Struk -->
        <div class="receipt-footer">
            <p class="mb-1 fw-bold text-dark">Terima Kasih atas Kunjungan Anda!</p>
            <p class="mb-0 text-muted" style="font-size: 11px;">Layanan rambut terbaik untuk penampilan elegan Anda.</p>
        </div>

        <!-- Tombol Aksi (Tidak ikut tercetak saat tombol Print ditekan) -->
        <div class="no-print mt-4 d-grid gap-2">
            <button onclick="window.print()" class="btn btn-dark fw-bold py-2 rounded-3 shadow-sm"
                style="background-color: #111;">
                <i class="bi bi-printer-fill me-2 text-warning"></i> Cetak Struk
            </button>
            <a href="<?= base_url('admin/pos') ?>" class="btn btn-light border py-2 rounded-3 fw-semibold text-dark">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Kasir (POS)
            </a>
        </div>
    </div>

</body>

</html>