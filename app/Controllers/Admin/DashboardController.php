<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServiceModel;
// use App\Models\ProductModel; // Aktifkan jika model produk sudah ada

class DashboardController extends BaseController
{
    public function index()
    {
        $serviceModel = new ServiceModel();
        // $productModel = new ProductModel();

        $data = [
            'title'          => 'Dashboard Admin - Barber POS',
            'total_services' => $serviceModel->countAllResults(),
            // 'total_products' => $productModel->countAllResults(),
        ];

        return view('admin/dashboard/index', $data);
    }
}
