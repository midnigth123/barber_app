<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['product_name', 'purchase_price', 'selling_price', 'stock', 'is_active'];
    protected $useTimestamps    = false;
}
