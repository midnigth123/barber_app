<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockOpnameDetailsTable extends Migration
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
            'opname_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'batch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'system_stock' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'physical_stock' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'difference' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('stock_opname_details');
    }

    public function down()
    {
        $this->forge->dropTable('stock_opname_details');
    }
}
