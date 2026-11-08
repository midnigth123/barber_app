<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use App\Models\ServiceModel;
use App\Models\ActivityLogModel; // <-- Import model log

class ServiceController extends BaseController
{
    public function index()
    {
        $serviceModel = new ServiceModel();

        $data = [
            'title'    => 'Kelola Layanan - Admin Panel',
            'services' => $serviceModel->findAll()
        ];
        
        return view('admin/services/index', $data);
    }

    public function create()
    {
        $serviceModel = new ServiceModel();
        $logModel     = new ActivityLogModel();

        $serviceName = $this->request->getPost('service_name');

        $serviceModel->save([
            'service_name' => $serviceName,
            'price'        => $this->request->getPost('price'),
            'is_active'    => 1
        ]);
        
        // Catat log aktivitas tambah layanan
        $logModel->save([
            'user_id'    => session()->get('id'),
            'activity'   => 'Menambahkan layanan baru: ' . $serviceName,
            'ip_address' => $this->request->getIPAddress()
        ]);
        // $this->recordLog('Menambahkan layanan baru: ' . $serviceName);
        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function update($id)
    {
        $serviceModel = new ServiceModel();
        $logModel     = new ActivityLogModel();

        $serviceName = $this->request->getPost('service_name');

        $serviceModel->update($id, [
            'service_name' => $serviceName,
            'price'        => $this->request->getPost('price'),
        ]);

        // Catat log aktivitas update layanan
        $logModel->save([
            'user_id'    => session()->get('id'),
            'activity'   => 'Memperbarui data layanan: ' . $serviceName,
            'ip_address' => $this->request->getIPAddress()
        ]);


        // $this->recordLog('Menambahkan layanan baru: ' . $serviceName);
        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil diperbarui!');
    }

    public function delete($id)
    {
        $serviceModel = new ServiceModel();
        $logModel     = new ActivityLogModel();

        // Ambil nama layanan sebelum dihapus untuk dicatat di log
        $service = $serviceModel->find($id);
        $serviceName = $service['service_name'] ?? 'ID #' . $id;

        $serviceModel->delete($id);

        // Catat log aktivitas hapus layanan
        $logModel->save([
            'user_id'    => session()->get('id'),
            'activity'   => 'Menghapus layanan: ' . $serviceName,
            'ip_address' => $this->request->getIPAddress()
        ]);

        // $this->recordLog('Menambahkan layanan baru: ' . $serviceName);
        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil dihapus!');
    }
}