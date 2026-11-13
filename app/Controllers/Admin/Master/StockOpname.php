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

    // Proses Simpan & Sinkronisasi Stok Fisik + Rekam Histori Detail
    public function store($opnameId)
    {
        $db = \Config\Database::connect();
        $opnameData = $this->request->getPost('opname');

        if (empty($opnameData)) {
            return redirect()->back()->with('error', 'Tidak ada data stok fisik yang diinput atau dikirim.');
        }

        try {
            $db->transStart();

            foreach ($opnameData as $batchId => $data) {
                $physicalStock = (int) ($data['physical_stock'] ?? 0);

                // Ambil data batch saat ini SEBELUM di-update (ini adalah Stok Sistem asli)
                $batch = $db->table('product_batches')->where('id', $batchId)->get()->getRowArray();

                if ($batch) {
                    $productId = $batch['product_id'];
                    $systemStock = (int) $batch['qty_remaining'];
                    $difference = $physicalStock - $systemStock;

                    // 1. Simpan ke tabel detail histori opname (stock_opname_details)
                    $existingDetail = $db->table('stock_opname_details')
                        ->where('opname_id', $opnameId)
                        ->where('batch_id', $batchId)
                        ->get()
                        ->getRowArray();

                    if ($existingDetail) {
                        $db->table('stock_opname_details')
                            ->where('id', $existingDetail['id'])
                            ->update([
                                'system_stock'   => $systemStock,
                                'physical_stock' => $physicalStock,
                                'difference'     => $difference
                            ]);
                    } else {
                        $db->table('stock_opname_details')->insert([
                            'opname_id'      => $opnameId,
                            'batch_id'       => $batchId,
                            'system_stock'   => $systemStock,
                            'physical_stock' => $physicalStock,
                            'difference'     => $difference
                        ]);
                    }

                    // 2. Update sisa stok batch di tabel master
                    $db->table('product_batches')
                        ->where('id', $batchId)
                        ->update([
                            'qty_remaining' => $physicalStock
                        ]);

                    // 3. Hitung total stok baru per produk
                    $sumResult = $db->table('product_batches')
                        ->selectSum('qty_remaining', 'total_stock')
                        ->where('product_id', $productId)
                        ->get()
                        ->getRowArray();

                    $totalNewStock = $sumResult['total_stock'] ?? 0;

                    // 4. Update stok utama pada produk
                    $db->table('products')
                        ->where('id', $productId)
                        ->update([
                            'stock' => $totalNewStock
                        ]);
                }
            }

            // 5. Update status sesi opname
            $db->table('stock_opnames')
                ->where('id', $opnameId)
                ->update([
                    'end_date'   => date('Y-m-d H:i:s'),
                    'status'     => 'completed',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal menyimpan stok opname.');
            }

            return redirect()->to('/admin/products/stock-opname')->with('success', 'Stok opname berhasil diselesaikan dan selisih tercatat akurat!');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'MySQL Raw Error: ' . $e->getMessage());
        }
    }

    // Ambil data detail opname via AJAX/JSON untuk Modal (Membaca dari Histori Detail)
    public function detail($opnameId)
    {
        $db = \Config\Database::connect();

        $opnameSession = $db->table('stock_opnames')->where('id', $opnameId)->get()->getRowArray();
        if (!$opnameSession) {
            return $this->response->setJSON(['status' => false, 'message' => 'Sesi tidak ditemukan']);
        }

        // Ambil data dari tabel histori detail opname yang sudah direkam saat penyimpanan
        $details = $db->table('stock_opname_details sod')
            ->select('sod.*, p.id as product_id, p.product_name, pb.batch_number, pb.expired_date')
            ->join('product_batches pb', 'pb.id = sod.batch_id', 'left')
            ->join('products p', 'p.id = pb.product_id', 'left')
            ->where('sod.opname_id', $opnameId)
            ->get()
            ->getResultArray();

        // Kelompokkan kembali ke format array produk -> batches agar cocok dengan JavaScript Anda
        $productsMap = [];
        foreach ($details as $row) {
            $prodId = $row['product_id'];
            if (!isset($productsMap[$prodId])) {
                $productsMap[$prodId] = [
                    'product_id'   => $prodId,
                    'product_name' => $row['product_name'],
                    'batches'      => []
                ];
            }

            $productsMap[$prodId]['batches'][] = [
                'batch_number'  => $row['batch_number'],
                'expired_date'  => $row['expired_date'],
                'system_stock'  => $row['system_stock'],
                'qty_remaining' => $row['physical_stock'], // Stok fisik real
                'difference'    => $row['difference']
            ];
        }

        return $this->response->setJSON([
            'status'        => true,
            'opnameSession' => $opnameSession,
            'products'      => array_values($productsMap)
        ]);
    }
}
