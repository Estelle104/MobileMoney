<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PrefixeExterne extends Migration
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
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => '3',
            ],
            'nom_operateur_externe' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'pourcentage_commission' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0,
            ],
            'id_operateur' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addUniqueKey(['code', 'id_operateur']);
        $this->forge->addForeignKey('id_operateur', 'operateur', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('prefixe_externe');
    }

    public function down()
    {
        $this->forge->dropTable('prefixe_externe');
    }
}
