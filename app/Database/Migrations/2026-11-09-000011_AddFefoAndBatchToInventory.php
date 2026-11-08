<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFefoAndBatchToInventory extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $fieldsPurchases = [];

        // Gunakan helper native CodeIgniter untuk cek kolom
        if (!$db->fieldExists('batch_number', 'product_purchases')) {
            $fieldsPurchases['batch_number'] = [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'invoice_number'
            ];
        }

        if (!$db->fieldExists('expired_date', 'product_purchases')) {
            $fieldsPurchases['expired_date'] = [
                'type'       => 'DATE',
                'null'       => true,
                'after'      => 'qty_received'
            ];
        }

        if (!$db->fieldExists('status', 'product_purchases')) {
            $fieldsPurchases['status'] = [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'confirmed'],
                'default'    => 'pending',
                'after'      => 'notes'
            ];
        }

        if (!empty($fieldsPurchases)) {
            $this->forge->addColumn('product_purchases', $fieldsPurchases);
        }

        // 2. Buat tabel product_batches jika belum ada
        if (!$db->tableExists('product_batches')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'product_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'batch_number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                ],
                'qty_initial' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'qty_remaining' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'expired_date' => [
                    'type'       => 'DATE',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('product_id');
            $this->forge->createTable('product_batches', true);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();

        $this->forge->dropTable('product_batches', true);
        
        if ($db->fieldExists('batch_number', 'product_purchases')) {
            $this->forge->dropColumn('product_purchases', 'batch_number');
        }
        if ($db->fieldExists('expired_date', 'product_purchases')) {
            $this->forge->dropColumn('product_purchases', 'expired_date');
        }
        if ($db->fieldExists('status', 'product_purchases')) {
            $this->forge->dropColumn('product_purchases', 'status');
        }
    }
}