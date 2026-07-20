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

        // 1. Types d'opérations
        $types = [
            ['id' => 1, 'libelle' => 'depot'],
            ['id' => 2, 'libelle' => 'retrait'],
            ['id' => 3, 'libelle' => 'transfert'],
        ];
        $this->db->table('type_operation')->insertBatch($types);

        // 2. Opérateurs
        $operateurs = [
            ['id' => 1, 'nom' => 'Orange Money', 'email' => 'orange@mobilemoney.mg', 'mdp' => password_hash('orange123', PASSWORD_BCRYPT)],
            ['id' => 2, 'nom' => 'MVola',        'email' => 'mvola@mobilemoney.mg',  'mdp' => password_hash('mvola123', PASSWORD_BCRYPT)],
            ['id' => 3, 'nom' => 'Airtel Money', 'email' => 'airtel@mobilemoney.mg', 'mdp' => password_hash('airtel123', PASSWORD_BCRYPT)],
        ];
        $this->db->table('operateur')->insertBatch($operateurs);

        // 3. Préfixes
        $prefixes = [
            ['id' => 1, 'code' => '032', 'id_operateur' => 1], // Orange
            ['id' => 2, 'code' => '037', 'id_operateur' => 1], // Orange
            ['id' => 3, 'code' => '034', 'id_operateur' => 2], // MVola
            ['id' => 4, 'code' => '038', 'id_operateur' => 2], // MVola
            ['id' => 5, 'code' => '033', 'id_operateur' => 3], // Airtel
        ];
        $this->db->table('prefixe')->insertBatch($prefixes);

        // 4. Barème de frais
        $baremes = [
            // DEPOT (gratuit)
            ['id_type_operation' => 1, 'montant_min' => 0,      'montant_max' => 100000000, 'frais' => 0],
            // RETRAIT
            ['id_type_operation' => 2, 'montant_min' => 0,      'montant_max' => 50000,   'frais' => 500],
            ['id_type_operation' => 2, 'montant_min' => 50001,  'montant_max' => 100000,  'frais' => 1000],
            ['id_type_operation' => 2, 'montant_min' => 100001, 'montant_max' => 500000,  'frais' => 2500],
            ['id_type_operation' => 2, 'montant_min' => 500001, 'montant_max' => 1000000, 'frais' => 5000],
            // TRANSFERT
            ['id_type_operation' => 3, 'montant_min' => 0,      'montant_max' => 50000,   'frais' => 300],
            ['id_type_operation' => 3, 'montant_min' => 50001,  'montant_max' => 100000,  'frais' => 700],
            ['id_type_operation' => 3, 'montant_min' => 100001, 'montant_max' => 500000,  'frais' => 1500],
            ['id_type_operation' => 3, 'montant_min' => 500001, 'montant_max' => 1000000, 'frais' => 3000],
        ];
        $this->db->table('bareme_frais')->insertBatch($baremes);

        // 5. Clients initiaux
        $clients = [
            ['id' => 1, 'numero' => '0321234567', 'id_prefixe' => 1, 'solde' => 500000.00],
            ['id' => 2, 'numero' => '0379999999', 'id_prefixe' => 2, 'solde' => 250000.00],
            ['id' => 3, 'numero' => '0341234567', 'id_prefixe' => 3, 'solde' => 750000.00],
            ['id' => 4, 'numero' => '0385555555', 'id_prefixe' => 4, 'solde' => 100000.00],
            ['id' => 5, 'numero' => '0339876543', 'id_prefixe' => 5, 'solde' => 300000.00],
        ];
        $this->db->table('client')->insertBatch($clients);

        // 6. Opérations (Historique)
        $operations = [
            [
                'id_client_source' => 1,
                'id_client_destinataire' => null,
                'id_type_operation' => 1, // Depot
                'montant' => 50000,
                'frais' => 0,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'id_client_source' => 5,
                'id_client_destinataire' => null,
                'id_type_operation' => 2, // Retrait
                'montant' => 20000,
                'frais' => 500,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],
            [
                'id_client_source' => 3,
                'id_client_destinataire' => 1,
                'id_type_operation' => 3, // Transfert
                'montant' => 100000,
                'frais' => 700,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-5 hours')),
            ],
            [
                'id_client_source' => 4,
                'id_client_destinataire' => 3,
                'id_type_operation' => 3, // Transfert
                'montant' => 50000,
                'frais' => 300,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-1 hours')),
            ],
        ];
        $this->db->table('operation')->insertBatch($operations);
    }
}