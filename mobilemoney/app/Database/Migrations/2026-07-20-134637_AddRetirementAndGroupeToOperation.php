<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRetirementAndGroupeToOperation extends Migration
{
    public function up()
    {
        $this->forge->addColumn('operation', [
            'frais_retrait_inclus' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null'    => false,
                'after'   => 'frais',
            ],
            'id_groupe_transfert' => [
                'type'    => 'VARCHAR',
                'constraint' => 36,
                'null'    => true,
                'after'   => 'frais_retrait_inclus',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('operation', ['frais_retrait_inclus', 'id_groupe_transfert']);
    }
}
