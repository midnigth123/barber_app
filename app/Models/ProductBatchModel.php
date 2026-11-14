<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductBatchModel extends Model
{
    protected $table            = 'product_batches';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['product_id', 'batch_number', 'qty_remaining', 'expired_date', 'created_at', 'updated_at'];
}