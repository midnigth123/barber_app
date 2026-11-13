<?php

namespace App\Controllers\Admin\Reports;

use App\Controllers\BaseController;
use App\Models\StockOpnameReportModel;

class StockOpname extends BaseController
{
    protected $opnameModel;

    public function __construct()
    {
        $this->opnameModel = new StockOpnameReportModel();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $data = [
            'title'      => 'Laporan Stok Opname',
            'opnames'    => $this->opnameModel->getFilteredStockOpname($startDate, $endDate),
            'start_date' => $startDate,
            'end_date'   => $endDate
        ];

        return view('admin/reports/stock_opname', $data);
    }
}