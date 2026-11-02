<?php

namespace App\Controllers\Admin\Transaksi;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class PosController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $serviceModel = new \App\Models\ServiceModel();
        $userModel    = new \App\Models\UserModel();

        $data = [
            'title'    => 'Kasir POS - Barbershop',
            'products' => $productModel->where('is_active', 1)->findAll(),
            'services' => $serviceModel->where('is_active', 1)->findAll(),
            'barbers'  => $userModel->where('role', 'barber')->findAll()
        ];

        return view('admin/pos/sales', $data);
    }

    public function store()
    {
        $transactionModel = new TransactionModel();
        $detailModel = new TransactionDetailModel();

        // Ambil data dari form POS
        $customerName  = $this->request->getPost('customer_name') ?? 'Umum';
        $paymentMethod = $this->request->getPost('payment_method');
        $cartItems     = $this->request->getPost('items');

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Keranjang belanja masih kosong!');
        }

        // Generate Invoice Code otomatis
        $invoiceCode = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // Hitung total amount keseluruhan
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // 1. Simpan ke tabel transactions
        $transactionId = $transactionModel->insert([
            'invoice_code'   => $invoiceCode,
            'customer_name'  => $customerName,
            'total_amount'   => $totalAmount,
            'payment_method' => $paymentMethod,
            'is_active'      => 1,
            'created_at'     => date('Y-m-d H:i:s')
        ], true);

        // 2. Simpan detail item ke tabel transaction_details & kurangi stok produk
        $productModel = new ProductModel();
        foreach ($cartItems as $item) {
            $subtotal = $item['price'] * $item['quantity'];

            // Hitung komisi kapster (30% jika layanan, 0 jika produk)
            $commissionEarned = 0;
            if (isset($item['item_type']) && $item['item_type'] === 'service') {
                $commissionRate = 0.30; 
                $commissionEarned = $subtotal * $commissionRate; 
            }

            // Hitung pendapatan bersih toko untuk item ini (Subtotal - Komisi Kapster)
            $shopRevenue = $subtotal - $commissionEarned; 

            $detailModel->insert([
                'transaction_id'    => $transactionId,
                'item_type'         => $item['item_type'],
                'item_id'           => $item['id'],
                'price'             => $item['price'],
                'quantity'          => $item['quantity'],
                'barber_id'         => $item['barber_id'] ?? 1,
                'commission_earned' => $commissionEarned,
                'shop_revenue'      => $shopRevenue,
                'is_active'         => 1
            ]);

            // Jika item berupa produk fisik, kurangi stoknya otomatis
            if ($item['item_type'] == 'product') {
                $product = $productModel->find($item['id']);
                if ($product) {
                    $newStock = $product['stock'] - $item['quantity'];
                    $productModel->update($item['id'], ['stock' => max(0, $newStock)]);
                }
            }
        }

        // 3. DIUBAH: Redirect langsung ke halaman cetak struk membawa ID transaksi
        return redirect()->to(base_url('admin/pos/receipt/' . $transactionId))
                         ->with('success', 'Transaksi berhasil disimpan!');
    }

    // TAMBAHKAN FUNGSI INI UNTUK MENAMPILKAN HALAMAN STRUK
    public function receipt($id)
    {
        $transactionModel = new TransactionModel();
        $detailModel = new TransactionDetailModel();

        // Ambil data transaksi utama beserta detail itemnya
        $transaction = $transactionModel->find($id);

        if (!$transaction) {
            return redirect()->to('admin/pos')->with('error', 'Data transaksi tidak ditemukan.');
        }

        // Ambil detail barang/layanan yang dibeli pada transaksi ini
        $details = $detailModel->where('transaction_id', $id)->findAll();

        $data = [
            'title'       => 'Struk Pembayaran - Barber POS',
            'transaction' => $transaction,
            'details'     => $details
        ];

        return view('admin/pos/receipt', $data);
    }
}