<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Nettoyage préalable (désactivation temporaire des clés étrangères pour vider sans erreur)
        $this->db->query('PRAGMA foreign_keys = OFF;');
        $this->db->table('operation')->emptyTable();
        $this->db->table('bareme_frais')->emptyTable();
        $this->db->table('client')->emptyTable();
        $this->db->table('prefixe')->emptyTable();
        $this->db->table('operateur')->emptyTable();
        $this->db->table('type_operation')->emptyTable();
        $this->db->query('PRAGMA foreign_keys = ON;');

        // 1. Types d'opérations (colonne libelle)
        $types = [
            ['id' => 1, 'libelle' => 'depot'],
            ['id' => 2, 'libelle' => 'retrait'],
            ['id' => 3, 'libelle' => 'transfert'],
        ];
        $this->db->table('type_operation')->insertBatch($types);

        // 2. Opérateurs
        $operateurs = [
            ['id' => 1, 'nom' => 'Orange', 'email' => 'contact@orange.mg', 'mdp' => password_hash('123456', PASSWORD_BCRYPT)],
            ['id' => 2, 'nom' => 'Airtel', 'email' => 'contact@airtel.mg', 'mdp' => password_hash('123456', PASSWORD_BCRYPT)],
            ['id' => 3, 'nom' => 'Telma',  'email' => 'contact@telma.mg',  'mdp' => password_hash('123456', PASSWORD_BCRYPT)],
        ];
        $this->db->table('operateur')->insertBatch($operateurs);

        // 3. Préfixes
        $prefixes = [
            ['id' => 1, 'code' => '032', 'id_operateur' => 1], // Orange
            ['id' => 2, 'code' => '037', 'id_operateur' => 1], // Orange
            ['id' => 3, 'code' => '033', 'id_operateur' => 2], // Airtel
            ['id' => 4, 'code' => '034', 'id_operateur' => 3], // Telma
            ['id' => 5, 'code' => '038', 'id_operateur' => 3], // Telma
        ];
        $this->db->table('prefixe')->insertBatch($prefixes);

        // 4. Barème de frais de test
        $baremes = [
            // Retrait (id_type_operation = 2)
            ['id' => 1, 'id_type_operation' => 2, 'montant_min' => 1000.00,  'montant_max' => 50000.00,  'frais' => 500.00],
            ['id' => 2, 'id_type_operation' => 2, 'montant_min' => 50001.00, 'montant_max' => 500000.00, 'frais' => 2000.00],
            // Dépôt (id_type_operation = 1, frais = 0)
            ['id' => 3, 'id_type_operation' => 1, 'montant_min' => 1000.00,  'montant_max' => 1000000.00,'frais' => 0.00],
        ];
        $this->db->table('bareme_frais')->insertBatch($baremes);

        // 5. Clients initiaux
        $clients = [
            ['id' => 1, 'numero' => '0321122233', 'id_prefixe' => 1, 'solde' => 50000.00],
            ['id' => 2, 'numero' => '0329988877', 'id_prefixe' => 1, 'solde' => 0.00],     // Idéal pour un test de dépôt
            ['id' => 3, 'numero' => '0345566677', 'id_prefixe' => 4, 'solde' => 20000.00],
        ];
        $this->db->table('client')->insertBatch($clients);
    }
}