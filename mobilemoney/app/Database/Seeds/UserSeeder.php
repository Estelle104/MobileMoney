<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'Jean',
                'email' => 'jean@gmail.com',
                'telephone' => '0340000000',
                'mdp' => password_hash('1234', PASSWORD_DEFAULT)
            ],
            [
                'nom' => 'Marie',
                'email' => 'marie@gmail.com',
                'telephone' => '0320000000',
                'mdp' => password_hash('1234', PASSWORD_DEFAULT)
            ]
        ];

        $this->db->table('clients')->insertBatch($data);
    }
}