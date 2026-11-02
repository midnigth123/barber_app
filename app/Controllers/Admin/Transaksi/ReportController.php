<?php

namespace App\Controllers\Admin\Transaksi;

use App\Controllers\BaseController;
use App\Models\TransactionModel;

class ReportController extends BaseController
{
    public function index()
    {
        $transactionModel = new TransactionModel();

        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $builder = $transactionModel->orderBy('created_at', 'DESC');

        if ($startDate && $endDate) {
            $builder->where('created_at >=', $startDate . ' 00:00:00')
                    ->where('created_at <=', $endDate . ' 23:59:59');
        }

        $transactions = $builder->findAll();

        // 1. Hitung total pendapatan HANYA untuk transaksi yang tidak dibatalkan
        $totalRevenue = 0;
        $successCount = 0;

        foreach ($transactions as $trx) {
            // Cek apakah status transaksi bukan cancelled
            if (!isset($trx['status']) || $trx['status'] !== 'cancelled') {
                $totalRevenue += $trx['total_amount'];
                $successCount++;
            }
        }

        $data = [
            'title'        => 'Laporan Transaksi - Barber POS',
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'successCount' => $successCount, // Kirim jumlah transaksi berhasil ke view
            'startDate'    => $startDate,
            'endDate'      => $endDate
        ];

        return view('admin/reports/transactions', $data);
    }
    // Tambahkan method ini di dalam ReportController.php
    public function getDetail($id)
    {
        $transactionModel = new \App\Models\TransactionModel();
        $detailModel      = new \App\Models\TransactionDetailModel();

        $transaction = $transactionModel->find($id);
        if (!$transaction) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data tidak ditemukan']);
        }

        $details = $detailModel->where('transaction_id', $id)->findAll();

        // Ambil nama item (produk/layanan) secara detail
        $formattedDetails = [];
        foreach ($details as $row) {
            $itemName = 'Item';
            if ($row['item_type'] == 'service') {
                $sModel = new \App\Models\ServiceModel();
                $item = $sModel->find($row['item_id']);
                $itemName = $item['name'] ?? 'Layanan Cukur';
            } else {
                $pModel = new \App\Models\ProductModel();
                $item = $pModel->find($row['item_id']);
                $itemName = $item['name'] ?? 'Produk';
            }

            $formattedDetails[] = [
                'item_name' => $itemName,
                'item_type' => ucfirst($row['item_type']),
                'quantity'  => $row['quantity'],
                'price'     => number_format($row['price'], 0, ',', '.'),
                'subtotal'  => number_format($row['price'] * $row['quantity'], 0, ',', '.')
            ];
        }

        return $this->response->setJSON([
            'status'      => true,
            'transaction' => $transaction,
            'details'     => $formattedDetails
        ]);
    }
    public function cancel($id)
    {
        $reason = $this->request->getPost('cancellation_reason');
        
        $transactionModel = new \App\Models\TransactionModel();
        $detailModel      = new \App\Models\TransactionDetailModel();
        $productModel     = new \App\Models\ProductModel();

        $transaction = $transactionModel->find($id);
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        // 1. Update status transaksi menjadi cancelled dan simpan alasannya
        $transactionModel->update($id, [
            'status'              => 'cancelled',
            'cancellation_reason' => $reason
        ]);

        // 2. Kembalikan stok produk jika transaksi dibatalkan
        $details = $detailModel->where('transaction_id', $id)->findAll();
        foreach ($details as $row) {
            if ($row['item_type'] == 'product') {
                $product = $productModel->find($row['item_id']);
                if ($product) {
                    $newStock = $product['stock'] + $row['quantity'];
                    $productModel->update($row['id'], ['stock' => $newStock]);
                }
            }
        }

        return redirect()->back()->with('success', 'Transaksi berhasil dibatalkan.');
    }
}