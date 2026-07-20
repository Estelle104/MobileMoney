<?php

namespace App\Models;

use CodeIgniter\Model;

class Operation extends Model
{
    protected $table            = 'operation';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id_client_source',
        'id_client_destinataire',
        'id_type_operation',
        'montant',
        'frais',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'date_transaction';
    protected $updatedField  = '';

    protected $validationRules = [
        'id_client_source'  => 'required|integer',
        'id_type_operation' => 'required|integer',
        'montant'           => 'required|decimal',
        'frais'             => 'required|decimal',
    ];

    /**
     * Historique complet d'un client (opérations émises + reçues).
     */
    // public function getHistoriqueByClient(int $idClient): array
    // {
    //     return $this->groupStart()
    //             ->where('id_client_source', $idClient)
    //             ->orWhere('id_client_destinataire', $idClient)
    //         ->groupEnd()
    //         ->orderBy('date_transaction', 'DESC')
    //         ->findAll();
    // }

    public function getHistoriqueByClient(int $idClient): array
    {

        return $this
            ->select(
                'operation.*,
             type_operation.libelle as type_operation,
             c1.numero as numero_source,
             c2.numero as numero_destinataire'
            )

            ->join(
                'type_operation',
                'type_operation.id = operation.id_type_operation'
            )

            ->join(
                'client c1',
                'c1.id = operation.id_client_source',
                'left'
            )

            ->join(
                'client c2',
                'c2.id = operation.id_client_destinataire',
                'left'
            )

            ->groupStart()

            ->where(
                'operation.id_client_source',
                $idClient
            )

            ->orWhere(
                'operation.id_client_destinataire',
                $idClient
            )

            ->groupEnd()

            ->orderBy(
                'operation.date_transaction',
                'DESC'
            )

            ->findAll();
    }

    /**
     * Total des frais collectés par type d'opération, pour un opérateur,
     * sur une période optionnelle.
     */
    public function getTotalFraisParType(int $idOperateur, ?string $dateDebut = null, ?string $dateFin = null): array
    {
        $builder = $this->select('type_operation.libelle, SUM(operation.frais) as total_frais')
            ->join('client', 'client.id = operation.id_client_source')
            ->join('prefixe', 'prefixe.id = client.id_prefixe')
            ->join('type_operation', 'type_operation.id = operation.id_type_operation')
            ->where('prefixe.id_operateur', $idOperateur)
            ->groupBy('type_operation.libelle');

        if ($dateDebut) {
            $builder->where('operation.date_transaction >=', $dateDebut);
        }
        if ($dateFin) {
            $builder->where('operation.date_transaction <=', $dateFin);
        }

        return $builder->findAll();
    }
}
