<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreationTableV1 extends Migration
{
    public function up()
    {
        // 1. Table Operateur
        $this->forge->addField([
            'id'    => ['type' => 'INT', 'auto_increment' => true],
            'nom'   => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'mdp'   => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('operateur', true);

        // 2. Table Prefixe
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'code'         => ['type' => 'VARCHAR', 'constraint' => 3, 'unique' => true],
            'id_operateur' => ['type' => 'INT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_operateur', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('prefixe', true);

        // 3. Table Client
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'numero'     => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true],
            'id_prefixe' => ['type' => 'INT'],
            'solde'      => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_prefixe', 'prefixe', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('client', true);

        // 4. Table Type Operation
        $this->forge->addField([
            'id'      => ['type' => 'INT', 'auto_increment' => true],
            'libelle' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('type_operation', true);

        // 5. Table Bareme Frais
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'auto_increment' => true],
            'id_type_operation' => ['type' => 'INT'],
            'montant_min'       => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'montant_max'       => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'frais'             => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_type_operation', 'type_operation', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bareme_frais', true);

        // 6. Table Operation
        $this->forge->addField([
            'id'                     => ['type' => 'INT', 'auto_increment' => true],
            'id_client_source'       => ['type' => 'INT'],
            'id_client_destinataire' => ['type' => 'INT', 'null' => true],
            'id_type_operation'      => ['type' => 'INT'],
            'montant'                => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'frais'                  => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'date_transaction'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_client_source', 'client', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_client_destinataire', 'client', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_type_operation', 'type_operation', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('operation', true);
    }

    public function down()
    {
        // Suppression dans l'ordre inverse des contraintes de clés étrangères
        $this->forge->dropTable('operation', true);
        $this->forge->dropTable('bareme_frais', true);
        $this->forge->dropTable('type_operation', true);
        $this->forge->dropTable('client', true);
        $this->forge->dropTable('prefixe', true);
        $this->forge->dropTable('operateur', true);
    }
}