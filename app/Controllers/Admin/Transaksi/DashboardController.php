<?php

namespace App\Controllers\Admin\Transaksi;

use App\Controllers\BaseController;
use App\Models\ServiceModel;
use App\Models\ProductModel;
use App\Models\ExpenseModel;
use App\Models\TransactionModel;
use App\Models\ProductBatchModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $serviceModel = new ServiceModel();
        $productModel = new ProductModel();
        $expenseModel = new ExpenseModel();
        $trxModel     = new TransactionModel();
        $batchModel   = new ProductBatchModel();

        // 1. Ambil data untuk Grafik Penjualan 7 Hari Terakhir (Menggunakan total_amount)
        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chartLabels[] = date('d M', strtotime($date));
            
            // Menghitung total_amount per hari
            $dailyTotal = $trxModel->where('DATE(created_at)', $date)
                                   ->selectSum('total_amount')
                                   ->get()->getRow()->total_amount ?? 0;
            $chartData[] = (float) $dailyTotal;
        }

        // 2. Produk dengan Stok Menipis (<= 5 pcs)
        $lowStockProducts = $productModel->where('stock <=', 5)->findAll(5);

        // 3. Batch Produk yang Mendekati Kedaluwarsa (dalam 30 hari ke depan, qty_remaining > 0)
        $thirtyDaysLater = date('Y-m-d', strtotime('+30 days'));
        $expiringBatches = [];
        if (class_exists('App\Models\ProductBatchModel')) {
            $expiringBatches = $batchModel->select('product_batches.*, products.product_name')
                                        ->join('products', 'products.id = product_batches.product_id')
                                        ->where('product_batches.qty_remaining >', 0)
                                        ->where('product_batches.expired_date <=', $thirtyDaysLater)
                                        ->orderBy('product_batches.expired_date', 'ASC')
                                        ->findAll(5);
        }

        // 4. Data Statistik Utama dan Transaksi Terbaru
        $data = [
            'title'               => 'Dashboard Admin - Barber POS',
            'total_services'      => $serviceModel->countAllResults(),
            'total_products'      => $productModel->countAllResults(),
            'today_revenue'       => $trxModel->where('DATE(created_at)', date('Y-m-d'))->selectSum('total_amount')->get()->getRow()->total_amount ?? 0,
            'total_expense'       => $expenseModel->where('MONTH(expense_date)', date('m'))->selectSum('amount')->get()->getRow()->amount ?? 0,
            'recent_transactions' => $trxModel->orderBy('created_at', 'DESC')->limit(5)->find(),
            'chart_labels'        => $chartLabels,
            'chart_data'          => $chartData,
            'low_stock_products'  => $lowStockProducts,
            'expiring_batches'    => $expiringBatches,
        ];

        return view('admin/dashboard/index', $data);
    }
}