<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class ProductPurchaseController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Ambil riwayat penerimaan produk beserta nama produknya
        $purchasesQuery = $db->table('product_purchases')
                             ->select('product_purchases.*, products.product_name')
                             ->join('products', 'products.id = product_purchases.product_id', 'left')
                             ->orderBy('product_purchases.created_at', 'DESC')
                             ->get();
                             
        $purchases = $purchasesQuery ? $purchasesQuery->getResultArray() : [];

        // Ambil daftar produk untuk pilihan di modal tambah
        $productsQuery = $db->table('products')->where('is_active', 1)->get();
        $products = $productsQuery ? $productsQuery->getResultArray() : [];

        $data = [
            'title'     => 'Penerimaan Produk (Restock) - Admin Panel',
            'purchases' => $purchases,
            'products'  => $products
        ];

        return view('admin/products/purchases', $data);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $productId     = $this->request->getPost('product_id');
        $qtyReceived   = (int) $this->request->getPost('qty_received');
        $purchasePrice = $this->request->getPost('purchase_price');
        $supplierName  = $this->request->getPost('supplier_name');
        $invoiceNumber = $this->request->getPost('invoice_number');
        $expiredDate   = $this->request->getPost('expired_date');
        $notes         = $this->request->getPost('notes');

        // Generate nomor batch otomatis oleh sistem
        $batchNumber = 'BAT-' . $productId . '-' . date('Ym') . '-' . strtoupper(substr(uniqid(), -4));

        // Simpan data restock sebagai pending (stok belum masuk sebelum dikonfirmasi)
        $db->table('product_purchases')->insert([
            'invoice_number' => $invoiceNumber,
            'batch_number'   => $batchNumber,
            'supplier_name'  => $supplierName,
            'product_id'     => $productId,
            'qty_received'   => $qtyReceived,
            'purchase_price' => $purchasePrice,
            'expired_date'   => $expiredDate,
            'user_id'        => session()->get('id') ?? null,
            'notes'          => $notes,
            'status'         => 'pending',
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/admin/products/receiving')->with('success', 'Penerimaan berhasil dicatat dengan No. Batch: ' . $batchNumber);
    }

    public function confirm($id)
    {
        $db = \Config\Database::connect();
        $logModel = new ActivityLogModel();

        // Ambil data pembelian berdasarkan ID
        $purchase = $db->table('product_purchases')->where('id', $id)->get()->getRowArray();

        if (!$purchase) {
            return redirect()->to('/admin/products/receiving')->with('error', 'Data penerimaan tidak ditemukan.');
        }

        // Cek apakah sudah dikonfirmasi sebelumnya
        if ($purchase['status'] === 'confirmed') {
            return redirect()->to('/admin/products/receiving')->with('error', 'Penerimaan ini sudah dikonfirmasi sebelumnya.');
        }

        $productId   = $purchase['product_id'];
        $qtyReceived = (int) $purchase['qty_received'];
        $batchNumber = $purchase['batch_number'];
        $expiredDate = $purchase['expired_date'];

        $db->transStart();

        // 1. Update status pembelian menjadi 'confirmed'
        $db->table('product_purchases')->where('id', $id)->update([
            'status' => 'confirmed'
        ]);

        // 2. Masukkan ke tabel product_batches agar terbaca oleh sistem FEFO di kasir
        $db->table('product_batches')->insert([
            'product_id'    => $productId,
            'batch_number'  => $batchNumber,
            'qty_initial'   => $qtyReceived,
            'qty_remaining' => $qtyReceived,
            'expired_date'  => $expiredDate,
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        // 3. Otomatis tambahkan stok utama ke tabel products
        $product = $db->table('products')->where('id', $productId)->get()->getRowArray();
        if ($product) {
            $newStock = $product['stock'] + $qtyReceived;
            $db->table('products')->where('id', $productId)->update([
                'stock'      => $newStock,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        // 4. Catat log aktivitas
        $logModel->save([
            'user_id'    => session()->get('id') ?? null,
            'activity'   => 'Konfirmasi restock & buat Batch FEFO untuk produk: ' . ($product['product_name'] ?? 'ID #' . $productId) . ' sebanyak ' . $qtyReceived . ' unit',
            'ip_address' => $this->request->getIPAddress()
        ]);

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->to('/admin/products/receiving')->with('error', 'Gagal mengkonfirmasi penerimaan.');
        }

        return redirect()->to('/admin/products/receiving')->with('success', 'Penerimaan dikonfirmasi! Batch FEFO aktif dan stok bertambah.');
    }
}