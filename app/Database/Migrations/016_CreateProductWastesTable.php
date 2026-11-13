<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductWastesTable extends Migration
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
            'waste_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'batch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'qty_waste' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'waste_date' => [
                'type' => 'DATE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('product_wastes');
    }

    public function down()
    {
        $this->forge->dropTable('product_wastes');
    }
}
