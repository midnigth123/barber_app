<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // Sesuaikan nama tabel Anda
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'username', 'email', 'password', 'role', 'is_active', 'created_at', 'updated_at'];

    // Aktifkan Timestamps otomatis
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}