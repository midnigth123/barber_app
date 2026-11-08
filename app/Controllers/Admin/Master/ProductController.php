<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\ActivityLogModel;

class ProductController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();

        $data = [
            'title'    => 'Kelola Produk - Admin Panel',
            'products' => $productModel->findAll()
        ];

        return view('admin/products/product', $data);
    }

    public function create()
    {
        $productModel = new ProductModel();
        $logModel     = new ActivityLogModel();
        $productName  = $this->request->getPost('product_name');

        $productModel->save([
            'product_name'   => $productName,
            'selling_price'  => $this->request->getPost('price'), 
            'stock'          => $this->request->getPost('stock'),
            'is_active'      => 1
        ]);

        // Catat log aktivitas menggunakan $logModel
        $logModel->save([
            'user_id'    => session()->get('id') ?? null,
            'activity'   => 'Menambahkan produk baru: ' . $productName,
            'ip_address' => $this->request->getIPAddress()
        ]);

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update($id)
    {
        $productModel = new ProductModel();
        $logModel     = new ActivityLogModel();
        $productName  = $this->request->getPost('product_name');

        $productModel->update($id, [
            'product_name'   => $productName,
            'selling_price'  => $this->request->getPost('price'), 
            'stock'          => $this->request->getPost('stock'),
        ]);

        // Catat log aktivitas update menggunakan $logModel
        $logModel->save([
            'user_id'    => session()->get('id') ?? null,
            'activity'   => 'Memperbarui data produk: ' . $productName,
            'ip_address' => $this->request->getIPAddress()
        ]);

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function delete($id)
    {
        $productModel = new ProductModel();
        $logModel     = new ActivityLogModel();
        
        // Ambil data produk dulu sebelum dihapus agar namanya tercatat di log
        $product     = $productModel->find($id);
        $productName = $product['product_name'] ?? 'ID #' . $id;

        $productModel->delete($id);

        // Catat log aktivitas hapus menggunakan $logModel
        $logModel->save([
            'user_id'    => session()->get('id') ?? null,
            'activity'   => 'Menghapus produk: ' . $productName,
            'ip_address' => $this->request->getIPAddress()
        ]);

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil dihapus!');
    }
    public function getBatches($productId)
    {
        $db = \Config\Database::connect();
        
        // Ambil semua batch aktif untuk produk ini berdasarkan urutan FEFO (expired terdekat di atas)
        $batches = $db->table('product_batches')
                    ->where('product_id', $productId)
                    ->orderBy('expired_date', 'ASC')
                    ->get()
                    ->getResultArray();

        return $this->response->setJSON($batches);
    }
}