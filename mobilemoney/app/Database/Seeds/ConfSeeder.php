<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ConfSeeder extends Seeder
{
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | OPERATEURS
        |--------------------------------------------------------------------------
        */
        $this->db->table('configuration')->insertBatch([
            [
                'pourcentage'   => 10,
            ],
        ]);
    }
}