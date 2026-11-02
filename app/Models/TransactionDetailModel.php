<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionDetailModel extends Model
{
    protected $table            = 'transaction_details';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'transaction_id',
        'item_type',
        'item_id',
        'price',
        'quantity',
        'barber_id',
        'commission_earned',
        'is_active'
    ];
    protected $useTimestamps    = false;
}
