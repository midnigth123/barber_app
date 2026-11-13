<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockOpnamesTable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Buat tabel stock_opnames jika belum ada
        if (!$db->tableExists('stock_opnames')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'opname_code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                ],
                'start_date' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'end_date' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['progress', 'completed'],
                    'default'    => 'progress',
                ],
                'notes' => [
                    'type' => 'TEXT',
                    'null' => true,
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
            $this->forge->addKey('opname_code');
            $this->forge->createTable('stock_opnames', true);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();

        // Hapus tabel dengan aman saat rollback
        if ($db->tableExists('stock_opnames')) {
            $this->forge->dropTable('stock_opnames', true);
        }
    }
}