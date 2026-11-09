<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;

class StockOpname extends BaseController
{
    // Halaman Utama: Menampilkan Daftar Sesi / Riwayat Stok Opname
    public function index()
    {
        $db = \Config\Database::connect();

        $opnameList = $db->table('stock_opnames')
                         ->orderBy('id', 'DESC')
                         ->get()
                         ->getResultArray();

        $data = [
            'title'      => 'Manajemen Stok Opname',
            'opnameList' => $opnameList
        ];

        return view('admin/products/stock_opname', $data);
    }

    // Aksi: Saat tombol "Mulai Stok Opname" diklik
    public function start()
    {
        $db = \Config\Database::connect();
        
        $today = date('Ymd');
        $lastOpname = $db->table('stock_opnames')
                         ->like('opname_code', 'OPN-' . $today, 'after')
                         ->orderBy('id', 'DESC')
                         ->get()
                         ->getRowArray();
                         
        $counter = 1;
        if ($lastOpname) {
            $lastCode = $lastOpname['opname_code'];
            $lastNumber = (int) substr($lastCode, -4);
            $counter = $lastNumber + 1;
        }
        
        $opnameCode = 'OPN-' . $today . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);

        $db->table('stock_opnames')->insert([
            'opname_code' => $opnameCode,
            'start_date'  => date('Y-m-d H:i:s'),
            'status'      => 'progress',
            'created_at'  => date('Y-m-d H:i:s')
        ]);
        
        $opnameId = $db->insertID();

        return redirect()->to('/admin/products/stock-opname/process/' . $opnameId);
    }

    // Halaman Form Input Fisik Produk Berbasis FEFO & Batch
    public function process($opnameId)
    {
        $db = \Config\Database::connect();

        $opnameSession = $db->table('stock_opnames')->where('id', $opnameId)->get()->getRowArray();
        if (!$opnameSession) {
            return redirect()->to('/admin/products/stock-opname')->with('error', 'Sesi stok opname tidak ditemukan.');
        }

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
            'title'         => 'Form Lakukan Stok Opname',
            'opnameSession' => $opnameSession,
            'products'      => $products
        ];

        return view('admin/products/stock_opname_create', $data);
    }

    // Proses Simpan & Sinkronisasi Stok Fisik (Diperbaiki & Disederhanakan)
    // Proses Simpan & Sinkronisasi Stok Fisik
    public function store($opnameId)
    {
        $db = \Config\Database::connect();
        $opnameData = $this->request->getPost('opname');

        if (empty($opnameData)) {
            return redirect()->back()->with('error', 'Tidak ada data stok fisik yang diinput atau dikirim.');
        }

        try {
            foreach ($opnameData as $batchId => $data) {
                $physicalStock = (int) ($data['physical_stock'] ?? 0);

                $batch = $db->table('product_batches')->where('id', $batchId)->get()->getRowArray();

                if ($batch) {
                    $productId = $batch['product_id'];

                    // 1. Update sisa stok batch (Tanpa updated_at jika tabel product_batches tidak memilikinya)
                    $db->table('product_batches')
                        ->where('id', $batchId)
                        ->update([
                            'qty_remaining' => $physicalStock
                        ]);

                    // 2. Hitung total stok baru
                    $sumResult = $db->table('product_batches')
                        ->selectSum('qty_remaining', 'total_stock')
                        ->where('product_id', $productId)
                        ->get()
                        ->getRowArray();
                    
                    $totalNewStock = $sumResult['total_stock'] ?? 0;

                    // 3. Update stok utama pada produk (Tanpa updated_at jika tabel products tidak memilikinya)
                    $db->table('products')
                        ->where('id', $productId)
                        ->update([
                            'stock' => $totalNewStock
                        ]);
                }
            }

            // 4. Update status sesi opname (Tabel stock_opnames PASTI punya updated_at & end_date sesuai gambar Navicat Anda)
            $db->table('stock_opnames')
                ->where('id', $opnameId)
                ->update([
                    'end_date'   => date('Y-m-d H:i:s'),
                    'status'     => 'completed',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            return redirect()->to('/admin/products/stock-opname')->with('success', 'Stok opname berhasil diselesaikan dan tanggal selesai tercatat!');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'MySQL Raw Error: ' . $e->getMessage());
        }
    }
    // Ambil data detail opname via AJAX/JSON untuk Modal
    // Ambil data detail opname via AJAX/JSON untuk Modal
    public function detail($opnameId)
    {
        $db = \Config\Database::connect();

        $opnameSession = $db->table('stock_opnames')->where('id', $opnameId)->get()->getRowArray();
        if (!$opnameSession) {
            return $this->response->setJSON(['status' => false, 'message' => 'Sesi tidak ditemukan']);
        }

        $products = $db->table('products p')->select('p.id as product_id, p.product_name, p.stock as total_stock')->get()->getResultArray();

        foreach ($products as &$product) {
            // Pastikan mengambil batch_number sesuai struktur database Anda
            $product['batches'] = $db->table('product_batches')
                ->select('id, product_id, batch_number, qty_remaining, expired_date')
                ->where('product_id', $product['product_id'])
                ->orderBy('expired_date', 'ASC')
                ->get()
                ->getResultArray();
        }

        return $this->response->setJSON([
            'status'        => true,
            'opnameSession' => $opnameSession,
            'products'      => $products
        ]);
    }
}