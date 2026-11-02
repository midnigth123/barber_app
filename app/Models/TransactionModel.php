<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'invoice_code',
        'customer_name',
        'total_amount',
        'payment_method',
        'is_active',
        'created_at'
    ];
    protected $useTimestamps    = false; // Disesuaikan dengan tabel yang tidak pakai updated_at
}
