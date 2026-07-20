<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateurSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('operateur')->insertBatch([
            [
                'nom'   => 'Orange Money',
                'email' => 'orange@mobilemoney.mg',
                'mdp'   => password_hash('orange123', PASSWORD_DEFAULT),
            ],
            [
                'nom'   => 'MVola',
                'email' => 'mvola@mobilemoney.mg',
                'mdp'   => password_hash('mvola123', PASSWORD_DEFAULT),
            ],
            [
                'nom'   => 'Airtel Money',
                'email' => 'airtel@mobilemoney.mg',
                'mdp'   => password_hash('airtel123', PASSWORD_DEFAULT),
            ],
        ]);

        $this->db->table('prefixe')->insertBatch([
            ['code' => '032', 'id_operateur' => 1],
            ['code' => '037', 'id_operateur' => 1],
            ['code' => '034', 'id_operateur' => 2],
            ['code' => '038', 'id_operateur' => 2],
            ['code' => '033', 'id_operateur' => 3],
        ]);
    }
}