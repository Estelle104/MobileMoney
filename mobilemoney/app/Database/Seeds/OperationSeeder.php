<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperationSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('client')->insertBatch([
            [
                'numero' => '0321234567',
                'id_prefixe' => 1,
                'solde' => 500000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numero' => '0339876543',
                'id_prefixe' => 2,
                'solde' => 300000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numero' => '0341234567',
                'id_prefixe' => 3,
                'solde' => 750000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numero' => '0385555555',
                'id_prefixe' => 4,
                'solde' => 100000,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numero' => '0379999999',
                'id_prefixe' => 5,
                'solde' => 250000,
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

        $baremes = [

            // DEPOT (gratuit)
            ['id_type_operation' => 1, 'montant_min' => 0, 'montant_max' => 50000, 'frais' => 0],
            ['id_type_operation' => 1, 'montant_min' => 50001, 'montant_max' => 1000000, 'frais' => 0],

            // RETRAIT
            ['id_type_operation' => 2, 'montant_min' => 0, 'montant_max' => 50000, 'frais' => 500],
            ['id_type_operation' => 2, 'montant_min' => 50001, 'montant_max' => 100000, 'frais' => 1000],
            ['id_type_operation' => 2, 'montant_min' => 100001, 'montant_max' => 500000, 'frais' => 2500],
            ['id_type_operation' => 2, 'montant_min' => 500001, 'montant_max' => 1000000, 'frais' => 5000],

            // TRANSFERT
            ['id_type_operation' => 3, 'montant_min' => 0, 'montant_max' => 50000, 'frais' => 300],
            ['id_type_operation' => 3, 'montant_min' => 50001, 'montant_max' => 100000, 'frais' => 700],
            ['id_type_operation' => 3, 'montant_min' => 100001, 'montant_max' => 500000, 'frais' => 1500],
            ['id_type_operation' => 3, 'montant_min' => 500001, 'montant_max' => 1000000, 'frais' => 3000],

        ];

        $this->db->table('bareme_frais')->insertBatch($baremes);

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
                'montant' => 20000,
                'frais' => 500,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 3,
                'id_client_destinataire' => 5,
                'id_type_operation' => 3,
                'montant' => 100000,
                'frais' => 700,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
            [
                'id_client_source' => 5,
                'id_client_destinataire' => 1,
                'id_type_operation' => 3,
                'montant' => 50000,
                'frais' => 300,
                'date_transaction' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
