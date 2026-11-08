<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class ServiceCategoryController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $categories = $db->table('service_categories')->get()->getResultArray();

        $data = [
            'title'      => 'Kategori Layanan - Admin Panel',
            'categories' => $categories
        ];

        return view('admin/services/categories', $data);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $logModel = new ActivityLogModel();
        
        $categoryName = $this->request->getPost('category_name');

        $db->table('service_categories')->insert([
            'category_name' => $categoryName,
            'description'   => $this->request->getPost('description'),
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        $logModel->save([
            'user_id'    => session()->get('id') ?? null,
            'activity'   => 'Menambahkan kategori layanan baru: ' . $categoryName,
            'ip_address' => $this->request->getIPAddress()
        ]);

        return redirect()->to('/admin/services/categories')->with('success', 'Kategori layanan berhasil ditambahkan!');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $logModel = new ActivityLogModel();

        $category = $db->table('service_categories')->where('id', $id)->get()->getRowArray();
        $catName  = $category['category_name'] ?? 'ID #' . $id;

        $db->table('service_categories')->where('id', $id)->delete();

        $logModel->save([
            'user_id'    => session()->get('id') ?? null,
            'activity'   => 'Menghapus kategori layanan: ' . $catName,
            'ip_address' => $this->request->getIPAddress()
        ]);

        return redirect()->to('/admin/services/categories')->with('success', 'Kategori layanan berhasil dihapus!');
    }
    public function update($id)
    {
        $db = \Config\Database::connect();
        $logModel = new ActivityLogModel();
        
        $categoryName = $this->request->getPost('category_name');

        $db->table('service_categories')->where('id', $id)->update([
            'category_name' => $categoryName,
            'description'   => $this->request->getPost('description'),
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        $logModel->save([
            'user_id'    => session()->get('id') ?? null,
            'activity'   => 'Memperbarui kategori layanan: ' . $categoryName,
            'ip_address' => $this->request->getIPAddress()
        ]);

        return redirect()->to('/admin/services/categories')->with('success', 'Kategori layanan berhasil diperbarui!');
    }
}