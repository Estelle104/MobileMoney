<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Configuration extends Migration
{
    public function up()
    {
       $this->forge->addColumn('operation', [
            'pourcentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'       => 0,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('configuration');
        
    }
}
