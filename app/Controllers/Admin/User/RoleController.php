<?php

namespace App\Controllers\Admin\User;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class RoleController extends BaseController
{
    public function index()
    {
        // Contoh data role statis atau ambil dari database model
        // Anda bisa menyesuaikan dengan tabel roles database Anda
        $db = \Config\Database::connect();
        
        $roles = [
            ['name' => 'admin', 'description' => 'Akses penuh ke seluruh fitur panel admin dan kasir.', 'users_count' => $db->table('users')->where('role', 'admin')->countAllResults()],
            ['name' => 'barber', 'description' => 'Akses khusus untuk melihat komisi dan layanan mandiri.', 'users_count' => $db->table('users')->where('role', 'barber')->countAllResults()],
            ['name' => 'kasir', 'description' => 'Akses terbatas hanya untuk transaksi POS dan kasir.', 'users_count' => $db->table('users')->where('role', 'kasir')->countAllResults()],
        ];

        $data = [
            'title' => 'Kelola Role Akses - Admin Panel',
            'roles' => $roles
        ];

        return view('admin/users/roles', $data);
    }

    // Method untuk menyimpan pengaturan permission role
    public function updatePermission($roleName)
{
    $logModel = new ActivityLogModel();
    $permissions = $this->request->getPost('permissions') ?? [];

    // Anda bisa menyimpan array $permissions ke database jika diperlukan tabel relasi khusus, 
    // atau sekadar mencatatnya ke log aktivitas sistem:

    $logModel->save([
        'user_id'    => session()->get('id') ?? null,
        'activity'   => 'Memperbarui hak akses (permission) untuk role: ' . strtoupper($roleName),
        'ip_address' => $this->request->getIPAddress()
    ]);

    return redirect()->to('admin/users/roles')->with('success', 'Hak akses untuk role ' . strtoupper($roleName) . ' berhasil diperbarui!');
}
}