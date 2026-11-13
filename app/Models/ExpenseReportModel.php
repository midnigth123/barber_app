<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseReportModel extends Model
{
    protected $table            = 'expenses'; // Sesuaikan jika nama tabel pengeluaran Anda berbeda
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'expense_code', 
        'expense_date', 
        'amount', 
        'description', 
        'category'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    // Method untuk mengambil data dengan filter tanggal
    public function getFilteredExpenses($startDate = null, $endDate = null)
    {
        $builder = $this;

        if (!empty($startDate) && !empty($endDate)) {
            $builder = $builder->where('expense_date >=', $startDate)
                               ->where('expense_date <=', $endDate);
        }

        return $builder->findAll();
    }
}