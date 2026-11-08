<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use App\Models\ServiceModel;
use App\Models\ActivityLogModel;

class ServiceCommissionController extends BaseController
{
    public function index()
    {
        $serviceModel = new ServiceModel();

        $data = [
            'title'    => 'Pengaturan Komisi Layanan - Admin Panel',
            'services' => $serviceModel->findAll()
        ];

        return view('admin/services/commissions', $data);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $logModel = new ActivityLogModel();

        $commissionRate = $this->request->getPost('commission_rate');
        
        // Ambil data layanan untuk keperluan log
        $service = $db->table('services')->where('id', $id)->get()->getRowArray();
        $serviceName = $service['service_name'] ?? 'Layanan #' . $id;

        // Update langsung menggunakan query builder database
        $db->table('services')->where('id', $id)->update([
            'commission_rate' => $commissionRate,
            'updated_at'      => date('Y-m-d H:i:s')
        ]);

        // Catat ke log aktivitas
        $logModel->save([
            'user_id'    => session()->get('id') ?? null,
            'activity'   => 'Memperbarui komisi layanan "' . $serviceName . '" menjadi ' . $commissionRate . '%',
            'ip_address' => $this->request->getIPAddress()
        ]);

        return redirect()->to('/admin/services/commissions')->with('success', 'Komisi layanan berhasil diperbarui!');
    }

}