<?php

namespace App\Controllers\Admin\Reports;

use App\Controllers\BaseController;
use App\Models\ProductWasteModel;

class Waste extends BaseController
{
    protected $productWasteModel;

    public function __construct()
    {
        $this->productWasteModel = new ProductWasteModel();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $data = [
            'title'      => 'Laporan Waste Produk',
            'wastes'     => $this->productWasteModel->getFilteredWaste($startDate, $endDate),
            'start_date' => $startDate,
            'end_date'   => $endDate
        ];

        return view('admin/reports/waste', $data);
    }
}