<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShopSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'shop_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'social_media' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'ppn_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'receipt_footer' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'logo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('shop_settings');

        // Insert data default baris pertama agar langsung siap digunakan
        $db = \Config\Database::connect();
        $db->table('shop_settings')->insert([
            'shop_name'      => 'Barbershop / Toko Anda',
            'phone'          => '081234567890',
            'social_media'   => '@barbershop',
            'ppn_percentage' => 0.00,
            'address'        => 'Alamat lengkap toko Anda di sini...',
            'receipt_footer' => 'Terima kasih atas kunjungan Anda!',
            'logo'           => null,
            'updated_at'     => date('Y-m-d H:i:s')
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('shop_settings');
    }
}