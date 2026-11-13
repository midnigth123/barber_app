<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnsToTransactions extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'success',
                'after'      => 'id', // Opsional: atur posisi kolom jika diperlukan
            ],
            'cancellation_reason' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'status',
            ],
        ];

        $this->forge->addColumn('transactions', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', ['status', 'cancellation_reason']);
    }
}
