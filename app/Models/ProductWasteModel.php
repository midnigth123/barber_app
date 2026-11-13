<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductWasteModel extends Model
{
    protected $table            = 'product_wastes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'waste_code', 
        'batch_id', 
        'product_id', 
        'qty_waste', 
        'reason', 
        'notes', 
        'waste_date'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected$createdField  = 'created_at';
    protected $updatedField  = '';

    // Method untuk filter tanggal sekaligus mengambil product_name via JOIN
    public function getFilteredWaste($startDate = null, $endDate = null)
    {
        $builder = $this->select('product_wastes.*, products.product_name as product_name')
                        ->join('products', 'products.id = product_wastes.product_id', 'left');

        if (!empty($startDate) && !empty($endDate)) {
            $builder = $builder->where('product_wastes.waste_date >=', $startDate)
                               ->where('product_wastes.waste_date <=', $endDate);
        }

        return $builder->findAll();
    }
}