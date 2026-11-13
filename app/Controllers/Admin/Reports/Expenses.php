<?php

namespace App\Controllers\Admin\Reports;

use App\Controllers\BaseController;
use App\Models\ExpenseReportModel;

class Expenses extends BaseController
{
    protected $expenseModel;

    public function __construct()
    {
        $this->expenseModel = new ExpenseReportModel();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $data = [
            'title'      => 'Laporan Pengeluaran',
            'expenses'   => $this->expenseModel->getFilteredExpenses($startDate, $endDate),
            'start_date' => $startDate,
            'end_date'   => $endDate
        ];

        return view('admin/reports/expenses', $data);
    }
}