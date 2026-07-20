<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ==========================
        // OPERATEURS
        // ==========================
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

        // ==========================
        // PREFIXES
        // ==========================
        $this->db->table('prefixe')->insertBatch([
            ['code' => '032', 'id_operateur' => 1],
            ['code' => '033', 'id_operateur' => 1],
            ['code' => '034', 'id_operateur' => 2],
            ['code' => '038', 'id_operateur' => 2],
            ['code' => '037', 'id_operateur' => 3],
        ]);

        // ==========================
        // CLIENTS
        // ==========================
        $this->db->table('client')->insertBatch([
            [
                'numero' => '0321234567',
                'id_prefixe' => 1,
                'solde' => 500000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numero' => '0327654321',
                'id_prefixe' => 1,
                'solde' => 300000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numero' => '0331111111',
                'id_prefixe' => 2,
                'solde' => 150000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numero' => '0341234567',
                'id_prefixe' => 3,
                'solde' => 700000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numero' => '0382222222',
                'id_prefixe' => 4,
                'solde' => 400000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numero' => '0349999999',
                'id_prefixe' => 3,
                'solde' => 100000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numero' => '0371234567',
                'id_prefixe' => 5,
                'solde' => 250000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numero' => '0378888888',
                'id_prefixe' => 5,
                'solde' => 800000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        // ==========================
        // TYPES D'OPERATION
        // ==========================
        $this->db->table('type_operation')->insertBatch([
            ['libelle' => 'Depot'],
            ['libelle' => 'Retrait'],
            ['libelle' => 'Transfert'],
        ]);

        // ==========================
        // BAREMES DES FRAIS
        // ==========================
        $this->db->table('bareme_frais')->insertBatch([
            // DEPOT
            [
                'id_type_operation' => 1,
                'montant_min' => 0,
                'montant_max' => 50000,
                'frais' => 0,
            ],
            [
                'id_type_operation' => 1,
                'montant_min' => 50001,
                'montant_max' => 1000000,
                'frais' => 0,
            ],

            // RETRAIT
            [
                'id_type_operation' => 2,
                'montant_min' => 0,
                'montant_max' => 50000,
                'frais' => 500,
            ],
            [
                'id_type_operation' => 2,
                'montant_min' => 50001,
                'montant_max' => 100000,
                'frais' => 1000,
            ],
            [
                'id_type_operation' => 2,
                'montant_min' => 100001,
                'montant_max' => 500000,
                'frais' => 2500,
            ],
            [
                'id_type_operation' => 2,
                'montant_min' => 500001,
                'montant_max' => 1000000,
                'frais' => 5000,
            ],

            // TRANSFERT
            [
                'id_type_operation' => 3,
                'montant_min' => 0,
                'montant_max' => 50000,
                'frais' => 300,
            ],
            [
                'id_type_operation' => 3,
                'montant_min' => 50001,
                'montant_max' => 100000,
                'frais' => 700,
            ],
            [
                'id_type_operation' => 3,
                'montant_min' => 100001,
                'montant_max' => 500000,
                'frais' => 1500,
            ],
            [
                'id_type_operation' => 3,
                'montant_min' => 500001,
                'montant_max' => 1000000,
                'frais' => 3000,
            ],
        ]);

        // ==========================
        // OPERATIONS
        // ==========================
        $this->db->table('operation')->insertBatch([
            [
                'id_client_source' => 1,
                'id_client_destinataire' => null,
                'id_type_operation' => 1,
                'montant' => 50000,
                'frais' => 0,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 2,
                'id_client_destinataire' => null,
                'id_type_operation' => 2,
                'montant' => 30000,
                'frais' => 500,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 3,
                'id_client_destinataire' => 7,
                'id_type_operation' => 3,
                'montant' => 40000,
                'frais' => 300,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 4,
                'id_client_destinataire' => 1,
                'id_type_operation' => 3,
                'montant' => 80000,
                'frais' => 700,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 5,
                'id_client_destinataire' => null,
                'id_type_operation' => 2,
                'montant' => 120000,
                'frais' => 2500,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 6,
                'id_client_destinataire' => null,
                'id_type_operation' => 1,
                'montant' => 200000,
                'frais' => 0,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 7,
                'id_client_destinataire' => 8,
                'id_type_operation' => 3,
                'montant' => 300000,
                'frais' => 1500,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 8,
                'id_client_destinataire' => 2,
                'id_type_operation' => 3,
                'montant' => 50000,
                'frais' => 300,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 1,
                'id_client_destinataire' => 4,
                'id_type_operation' => 3,
                'montant' => 150000,
                'frais' => 1500,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 2,
                'id_client_destinataire' => null,
                'id_type_operation' => 2,
                'montant' => 600000,
                'frais' => 5000,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 5,
                'id_client_destinataire' => 3,
                'id_type_operation' => 3,
                'montant' => 90000,
                'frais' => 700,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 7,
                'id_client_destinataire' => null,
                'id_type_operation' => 1,
                'montant' => 100000,
                'frais' => 0,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}