<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;

class StockOpname extends BaseController
{
    // Halaman Utama: Menampilkan Daftar Riwayat / Sesi Stok Opname dengan tombol "Mulai Stok Opname"
    public function index()
    {
        $db = \Config\Database::connect();

        // Ambil data batch atau riwayat penyesuaian terakhir
        $builder = $db->table('product_batches pb');
        $builder->select('pb.*, p.product_name');
        $builder->join('products p', 'p.id = pb.product_id');
        $builder->orderBy('pb.updated_at', 'DESC');
        $history = $builder->get()->getResultArray();

        $data = [
            'title'   => 'Riwayat Stok Opname',
            'history' => $history
        ];

        return view('admin/products/stock_opname', $data);
    }

    // Halaman Form: Muncul saat tombol "Mulai Stok Opname" diklik
    public function create()
    {
        $db = \Config\Database::connect();

        // Ambil produk dan batch yang aktif (qty_remaining > 0 atau semua batch)
        $builder = $db->table('products p');
        $builder->select('p.id as product_id, p.product_name, p.stock as total_stock');
        $products = $builder->get()->getResultArray();

        foreach ($products as &$product) {
            $product['batches'] = $db->table('product_batches')
                ->where('product_id', $product['product_id'])
                ->orderBy('expired_date', 'ASC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'    => 'Form Lakukan Stok Opname',
            'products' => $products
        ];

        return view('admin/products/stock_opname_create', $data);
    }

    // Proses Simpan & Sinkronisasi
    public function store()
    {
        $db = \Config\Database::connect();
        $logModel = new \App\Models\ActivityLogModel();

        // Cek apakah ada data opname yang dikirim dari form
        $opnameData = $this->request->getPost('opname');

        if (empty($opnameData)) {
            return redirect()->back()->with('error', 'Tidak ada data stok fisik yang diinput atau dikirim.');
        }

        $db->transStart();

        foreach ($opnameData as $batchId => $data) {
            $physicalStock = (int) ($data['physical_stock'] ?? 0);

            $batch = $db->table('product_batches')->where('id', $batchId)->get()->getRowArray();

            if ($batch) {
                // 1. Update sisa stok batch
                $db->table('product_batches')
                    ->where('id', $batchId)
                    ->update([
                        'qty_remaining' => $physicalStock,
                        'updated_at'    => date('Y-m-d H:i:s')
                    ]);

                // 2. Rekalkulasi total stok produk berdasarkan akumulasi seluruh batch
                $totalNewStock = $db->table('product_batches')
                    ->selectSum('qty_remaining')
                    ->where('product_id', $batch['product_id'])
                    ->get()
                    ->getRow()
                    ->qty_remaining ?? 0;

                $db->table('products')
                    ->where('id', $batch['product_id'])
                    ->update(['stock' => $totalNewStock]);
            }
        }

        $db->transComplete();

        if (class_exists('\\App\\Models\\ActivityLogModel')) {
            $logModel->save([
                'user_id'    => session()->get('id') ?? null,
                'activity'   => 'Melakukan proses Stok Opname dan sinkronisasi batch',
                'ip_address' => $this->request->getIPAddress()
            ]);
        }

        return redirect()->to('/admin/products/stock-opname')->with('success', 'Stok opname berhasil disimpan dan stok produk telah disinkronkan!');
    }
}
