<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SyncProductPurchasesColumns extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Cek dan tambahkan kolom batch_number di product_purchases jika belum ada
        if (!$db->fieldExists('batch_number', 'product_purchases')) {
            $this->forge->addColumn('product_purchases', [
                'batch_number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'invoice_number'
                ]
            ]);
        }

        // 2. Cek dan tambahkan kolom updated_at di product_purchases jika belum ada
        if (!$db->fieldExists('updated_at', 'product_purchases')) {
            $this->forge->addColumn('product_purchases', [
                'updated_at' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'created_at'
                ]
            ]);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();

        // Beri pengaman agar tidak error jika tabel belum/sudah dihapus
        if ($db->tableExists('product_purchases')) {
            if ($db->fieldExists('batch_number', 'product_purchases')) {
                $this->forge->dropColumn('product_purchases', 'batch_number');
            }
            if ($db->fieldExists('updated_at', 'product_purchases')) {
                $this->forge->dropColumn('product_purchases', 'updated_at');
            }
        }
    }
}