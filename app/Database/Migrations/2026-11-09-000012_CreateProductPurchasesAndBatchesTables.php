<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductPurchasesAndBatchesTables extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Buat tabel product_purchases (Restock / Penerimaan Barang)
        if (!$db->tableExists('product_purchases')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'invoice_number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                ],
                'batch_number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                ],
                'supplier_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                ],
                'product_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'qty_received' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'purchase_price' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'default'    => '0.00',
                ],
                'expired_date' => [
                    'type'       => 'DATE',
                    'null'       => true,
                ],
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'notes' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['pending', 'confirmed'],
                    'default'    => 'pending',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('product_id');
            $this->forge->createTable('product_purchases', true);
        }

        // 2. Buat tabel product_batches (Pelacakan Sisa Stok per Batch / FEFO)
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
                'updated_at' => [
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
        $this->forge->dropTable('product_batches', true);
        $this->forge->dropTable('product_purchases', true);
    }
}