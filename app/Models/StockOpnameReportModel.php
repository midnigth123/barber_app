<?php

namespace App\Models;

use CodeIgniter\Model;

class StockOpnameReportModel extends Model
{
    protected $table            = 'stock_opname_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'opname_id', 
        'batch_id', 
        'system_stock', 
        'physical_stock', 
        'difference', 
        'notes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    // Method untuk mengambil data dengan join ke stock_opnames, product_batches, dan products
    public function getFilteredStockOpname($startDate = null, $endDate = null)
    {
        $builder = $this->select('stock_opname_details.*, stock_opnames.opname_code, stock_opnames.start_date, products.product_name as product_name, product_batches.batch_number')
                        ->join('stock_opnames', 'stock_opnames.id = stock_opname_details.opname_id', 'left')
                        ->join('product_batches', 'product_batches.id = stock_opname_details.batch_id', 'left')
                        ->join('products', 'products.id = product_batches.product_id', 'left');

        if (!empty($startDate) && !empty($endDate)) {
            $builder = $builder->where('stock_opnames.start_date >=', $startDate)
                               ->where('stock_opnames.start_date <=', $endDate);
        }

        return $builder->findAll();
    }
}