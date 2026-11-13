<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;

class Waste extends BaseController
{
    // Halaman Utama: Daftar Riwayat Barang Waste/Rusak/Expired
    public function index()
    {
        $db = \Config\Database::connect();

        // Ambil data waste digabung dengan nama produk dan nomor batch
        $wastes = $db->table('product_wastes pw')
            ->select('pw.*, p.product_name, pb.batch_number, pb.expired_date')
            ->join('products p', 'p.id = pw.product_id', 'left')
            ->join('product_batches pb', 'pb.id = pw.batch_id', 'left')
            ->orderBy('pw.id', 'DESC')
            ->get()
            ->getResultArray();

        // Ambil daftar batch produk yang aktif untuk pilihan pada modal tambah/edit
        $batches = $db->table('product_batches pb')
            ->select('pb.id as batch_id, pb.batch_number, pb.qty_remaining, pb.expired_date, p.product_name, p.id as product_id')
            ->join('products p', 'p.id = pb.product_id', 'left')
            ->orderBy('pb.expired_date', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title'   => 'Manajemen Waste Produk',
            'wastes'  => $wastes,
            'batches' => $batches
        ];

        return view('admin/products/waste', $data);
    }

    // Proses Simpan Data Waste & Potong Stok Otomatis
    public function store()
    {
        $db = \Config\Database::connect();

        $batchId   = $this->request->getPost('batch_id');
        $qtyWaste  = (int) $this->request->getPost('qty_waste');
        $reason    = $this->request->getPost('reason');
        $notes     = $this->request->getPost('notes');
        $wasteDate = $this->request->getPost('waste_date');

        $batch = $db->table('product_batches')->where('id', $batchId)->get()->getRowArray();
        if (!$batch) {
            return redirect()->back()->with('error', 'Batch produk tidak ditemukan.');
        }

        if ($qtyWaste > $batch['qty_remaining']) {
            return redirect()->back()->with('error', 'Jumlah waste melebihi sisa stok batch yang tersedia (' . $batch['qty_remaining'] . ').');
        }

        $productId = $batch['product_id'];

        try {
            $db->transStart();

            $wasteCode = 'WST-' . date('Ymd') . '-' . rand(1000, 9999);

            $db->table('product_wastes')->insert([
                'waste_code' => $wasteCode,
                'batch_id'   => $batchId,
                'product_id' => $productId,
                'qty_waste'  => $qtyWaste,
                'reason'     => $reason,
                'notes'      => $notes,
                'waste_date' => $wasteDate,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Kurangi stok batch
            $newBatchQty = $batch['qty_remaining'] - $qtyWaste;
            $db->table('product_batches')->where('id', $batchId)->update(['qty_remaining' => $newBatchQty]);

            // Hitung ulang total stok produk utama
            $sumResult = $db->table('product_batches')
                ->selectSum('qty_remaining', 'total_stock')
                ->where('product_id', $productId)
                ->get()
                ->getRowArray();

            $db->table('products')->where('id', $productId)->update(['stock' => $sumResult['total_stock'] ?? 0]);

            $db->transComplete();

            return redirect()->to('/admin/products/waste')->with('success', 'Data waste berhasil dicatat dan stok gudang disesuaikan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // Proses Update / Edit Data Waste
    public function update($id)
    {
        $db = \Config\Database::connect();

        $oldWaste = $db->table('product_wastes')->where('id', $id)->get()->getRowArray();
        if (!$oldWaste) {
            return redirect()->to('/admin/products/waste')->with('error', 'Data waste tidak ditemukan.');
        }

        $newBatchId  = $this->request->getPost('batch_id');
        $newQtyWaste = (int) $this->request->getPost('qty_waste');
        $reason      = $this->request->getPost('reason');
        $notes       = $this->request->getPost('notes');
        $wasteDate   = $this->request->getPost('waste_date');

        try {
            $db->transStart();

            // 1. Kembalikan stok lama terlebih dahulu ke batch aslinya
            $oldBatch = $db->table('product_batches')->where('id', $oldWaste['batch_id'])->get()->getRowArray();
            if ($oldBatch) {
                $restoredQty = $oldBatch['qty_remaining'] + $oldWaste['qty_waste'];
                $db->table('product_batches')->where('id', $oldWaste['batch_id'])->update(['qty_remaining' => $restoredQty]);
            }

            // 2. Validasi batch baru dan stoknya
            $newBatch = $db->table('product_batches')->where('id', $newBatchId)->get()->getRowArray();
            if (!$newBatch || $newQtyWaste > $newBatch['qty_remaining']) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Batch baru tidak valid atau jumlah waste melebihi sisa stok.');
            }

            // 3. Potong stok batch baru
            $updatedBatchQty = $newBatch['qty_remaining'] - $newQtyWaste;
            $db->table('product_batches')->where('id', $newBatchId)->update(['qty_remaining' => $updatedBatchQty]);

            // 4. Update data waste
            $db->table('product_wastes')->where('id', $id)->update([
                'batch_id'   => $newBatchId,
                'product_id' => $newBatch['product_id'],
                'qty_waste'  => $newQtyWaste,
                'reason'     => $reason,
                'notes'      => $notes,
                'waste_date' => $wasteDate
            ]);

            // 5. Sinkronisasi ulang total stok produk (produk lama & produk baru jika berbeda)
            $productIds = array_unique([$oldWaste['product_id'], $newBatch['product_id']]);
            foreach ($productIds as $prodId) {
                $sumResult = $db->table('product_batches')
                    ->selectSum('qty_remaining', 'total_stock')
                    ->where('product_id', $prodId)
                    ->get()
                    ->getRowArray();

                $db->table('products')->where('id', $prodId)->update(['stock' => $sumResult['total_stock'] ?? 0]);
            }

            $db->transComplete();

            return redirect()->to('/admin/products/waste')->with('success', 'Data waste berhasil diperbarui dan stok disesuaikan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // Hapus Data Waste & Kembalikan Stok
    public function delete($id)
    {
        $db = \Config\Database::connect();

        $waste = $db->table('product_wastes')->where('id', $id)->get()->getRowArray();
        if (!$waste) {
            return redirect()->to('/admin/products/waste')->with('error', 'Data waste tidak ditemukan.');
        }

        try {
            $db->transStart();

            $batch = $db->table('product_batches')->where('id', $waste['batch_id'])->get()->getRowArray();
            if ($batch) {
                $restoredBatchQty = $batch['qty_remaining'] + $waste['qty_waste'];
                $db->table('product_batches')->where('id', $waste['batch_id'])->update(['qty_remaining' => $restoredBatchQty]);

                $sumResult = $db->table('product_batches')
                    ->selectSum('qty_remaining', 'total_stock')
                    ->where('product_id', $waste['product_id'])
                    ->get()
                    ->getRowArray();

                $db->table('products')->where('id', $waste['product_id'])->update(['stock' => $sumResult['total_stock'] ?? 0]);
            }

            $db->table('product_wastes')->where('id', $id)->delete();

            $db->transComplete();

            return redirect()->to('/admin/products/waste')->with('success', 'Data waste berhasil dihapus dan stok dikembalikan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/admin/products/waste')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
