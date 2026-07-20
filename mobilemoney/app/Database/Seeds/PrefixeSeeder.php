<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixeSeeder extends Seeder
{
    public function run()
    {
        // 1. D'abord insérer les opérateurs car la table prefixe en dépend (Foreign Key)
        $operateurs = [
            ['id' => 1, 'nom' => 'Orange', 'email' => 'contact@orange.mg', 'mdp' => password_hash('123456', PASSWORD_BCRYPT)],
            ['id' => 2, 'nom' => 'Airtel', 'email' => 'contact@airtel.mg', 'mdp' => password_hash('123456', PASSWORD_BCRYPT)],
            ['id' => 3, 'nom' => 'Telma',  'email' => 'contact@telma.mg',  'mdp' => password_hash('123456', PASSWORD_BCRYPT)],
        ];

        $this->db->table('operateur')->insertBatch($operateurs);

        // 2. Insérer les préfixes associés
        $prefixes = [
            ['code' => '032', 'id_operateur' => 1], // Orange
            ['code' => '037', 'id_operateur' => 1], // Orange
            ['code' => '033', 'id_operateur' => 2], // Airtel
            ['code' => '034', 'id_operateur' => 3], // Telma
            ['code' => '038', 'id_operateur' => 3], // Telma
        ];

        $this->db->table('prefixe')->insertBatch($prefixes);
    }
}