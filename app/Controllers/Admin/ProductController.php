<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;

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

        $productModel->save([
            'product_name'   => $this->request->getPost('product_name'),
            'purchase_price' => 0,
            'selling_price'  => $this->request->getPost('price'), // Ubah jika form mengirim name="selling_price" atau tetap biarkan 'price' jika input formnya name="price"
            'stock'          => $this->request->getPost('stock'),
            'is_active'      => 1
        ]);

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update($id)
    {
        $productModel = new ProductModel();

        $productModel->update($id, [
            'product_name'   => $this->request->getPost('product_name'),
            'selling_price'  => $this->request->getPost('price'), // Pastikan ini menangkap input harga dengan benar
            'stock'          => $this->request->getPost('stock'),
        ]);

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil diperbarui!');
    }

    // <-- METHOD UNTUK HAPUS PRODUK -->
    public function delete($id)
    {
        $productModel = new ProductModel();

        $productModel->delete($id);

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil dihapus!');
    }
}
