<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddServiceCategoriesAndCommission extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Buat tabel service_categories (Gunakan parameter true/if not exists agar aman)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'category_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'description' => [
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
        $this->forge->createTable('service_categories', true);

        // 2. Tambahkan kolom commission_rate ke tabel services (Hanya jika kolom belum ada)
        if (!$db->fieldExists('commission_rate', 'services')) {
            $this->forge->addColumn('services', [
                'commission_rate' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '5,2',
                    'default'    => '30.00',
                    'after'      => 'price',
                    'null'       => true,
                ],
            ]);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();

        $this->forge->dropTable('service_categories', true);
        
        // Hapus kolom hanya jika kolomnya ada
        if ($db->fieldExists('commission_rate', 'services')) {
            $this->forge->dropColumn('services', 'commission_rate');
        }
    }
}