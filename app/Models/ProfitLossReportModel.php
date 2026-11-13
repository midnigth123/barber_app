<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfitLossReportModel extends Model
{
    // Method untuk menghitung total pendapatan dan pengeluaran berdasarkan filter tanggal
    public function getProfitLossData($startDate = null, $endDate = null)
    {
        $db = \Config\Database::connect();

        // 1. Hitung Total Pendapatan dari tabel transactions menggunakan total_amount & created_at
        $salesBuilder = $db->table('transactions');
        
        if (!empty($startDate) && !empty($endDate)) {
            $salesBuilder->where('created_at >=', $startDate . ' 00:00:00')
                         ->where('created_at <=', $endDate . ' 23:59:59');
        }
        
        $salesBuilder->selectSum('total_amount', 'total_revenue'); 
        $salesResult = $salesBuilder->get()->getRowArray();
        $totalRevenue = $salesResult['total_revenue'] ?? 0;

        // 2. Hitung Total Pengeluaran dari tabel expenses menggunakan expense_date
        $expenseBuilder = $db->table('expenses');
        if (!empty($startDate) && !empty($endDate)) {
            $expenseBuilder->where('expense_date >=', $startDate)
                           ->where('expense_date <=', $endDate);
        }
        $expenseBuilder->selectSum('amount', 'total_expense');
        $expenseResult = $expenseBuilder->get()->getRowArray();
        $totalExpense = $expenseResult['total_expense'] ?? 0;

        // 3. Hitung Laba Bersih (Net Profit)
        $netProfit = $totalRevenue - $totalExpense;

        return [
            'total_revenue' => $totalRevenue,
            'total_expense' => $totalExpense,
            'net_profit'    => $netProfit
        ];
    }
}