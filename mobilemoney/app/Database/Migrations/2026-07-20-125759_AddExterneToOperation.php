<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExterneToOperation extends Migration
{
    public function up()
    {
        $this->forge->addColumn('operation', [
            'numero_destinataire_externe' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'id_prefixe_externe' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ]
        ]);

        $this->forge->addForeignKey('id_prefixe_externe', 'prefixe_externe', 'id', 'CASCADE', 'CASCADE');
        $this->forge->processIndexes('operation');
    }

    public function down()
    {
        $this->forge->dropForeignKey('operation', 'operation_id_prefixe_externe_foreign');
        $this->forge->dropColumn('operation', ['numero_destinataire_externe', 'id_prefixe_externe']);
    }
}
