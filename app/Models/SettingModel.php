<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'shop_settings';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'shop_name', 
        'address', 
        'phone', 
        'social_media', 
        'logo', 
        'receipt_footer', 
        'ppn_percentage'
    ];
}