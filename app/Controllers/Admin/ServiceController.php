<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServiceModel;

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

        $serviceModel->save([
            'service_name' => $this->request->getPost('service_name'),
            'price'        => $this->request->getPost('price'),
            'is_active'    => 1
        ]);

        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil ditambahkan!');
    }

    // <-- TAMBAHKAN METHOD UPDATE DI SINI -->
    public function update($id)
    {
        $serviceModel = new ServiceModel();

        $serviceModel->update($id, [
            'service_name' => $this->request->getPost('service_name'),
            'price'        => $this->request->getPost('price'),
        ]);

        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil diperbarui!');
    }

    // <-- TAMBAHKAN METHOD DELETE DI SINI -->
    public function delete($id)
    {
        $serviceModel = new ServiceModel();

        $serviceModel->delete($id);

        return redirect()->to('/admin/services')->with('success', 'Layanan berhasil dihapus!');
    }
}
