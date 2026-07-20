<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReglementExterne extends Migration
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
            'id_operateur' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nom_operateur_externe' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'montant' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'date_reglement' => [
                'type' => 'DATE',
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
        $this->forge->addForeignKey('id_operateur', 'operateur', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('reglement_externe');
    }

    public function down()
    {
        $this->forge->dropTable('reglement_externe');
    }
}
