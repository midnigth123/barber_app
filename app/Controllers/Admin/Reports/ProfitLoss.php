<?php

namespace App\Controllers\Admin\Reports;

use App\Controllers\BaseController;
use App\Models\ProfitLossReportModel;

class ProfitLoss extends BaseController
{
    protected $profitLossModel;

    public function __construct()
    {
        $this->profitLossModel = new ProfitLossReportModel();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $reportData = $this->profitLossModel->getProfitLossData($startDate, $endDate);

        $data = [
            'title'         => 'Laporan Laba Rugi',
            'total_revenue' => $reportData['total_revenue'],
            'total_expense' => $reportData['total_expense'],
            'net_profit'    => $reportData['net_profit'],
            'start_date'    => $startDate,
            'end_date'      => $endDate
        ];

        return view('admin/reports/profit_loss', $data);
    }
}